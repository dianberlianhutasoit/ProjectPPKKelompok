<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ReservationApprovalController extends Controller
{
    public function index(Request $request)
    {
        // Sanitasi input sorting demi keamanan
        $allowedSorts = ['created_at', 'start_time', 'end_time'];
        $allowedOrders = ['asc', 'desc'];

        $sortBy = in_array($request->query('sort_by'), $allowedSorts) ? $request->query('sort_by') : 'created_at';
        $sortOrder = in_array(strtolower($request->query('sort_order')), $allowedOrders) ? strtolower($request->query('sort_order')) : 'asc';

        $filters = [
            'status' => $request->query('status', 'PENDING'),
            'facility_id' => $request->query('facility_id'),
            'sort_by' => $sortBy,
            'sort_order' => $sortOrder,
        ];

        $reservations = Reservation::with(['user', 'facility'])
            ->filterAndSort($filters)
            ->paginate(10)
            ->appends($request->query());

        return view('staff.reservations.index', compact('reservations', 'filters'));
    }

    public function approve($id)
    {
        try {
            DB::beginTransaction();

            // Lock baris reservasi untuk mencegah race condition
            $reservation = Reservation::where('id', $id)
                ->where('status', 'PENDING')
                ->lockForUpdate()
                ->firstOrFail();

            // Pengecekan bentrok HANYA dengan reservasi lain yang sudah disetujui (APPROVED)
            $overlap = Reservation::where('facility_id', $reservation->facility_id)
                ->where('id', '!=', $reservation->id)
                ->where('status', 'APPROVED')
                ->where('start_time', '<', $reservation->end_time)
                ->where('end_time', '>', $reservation->start_time)
                ->exists();

            if ($overlap) {
                DB::rollBack();

                return back()->with('error', 'Reservasi tidak dapat disetujui karena jadwal bentrok dengan reservasi lain yang sudah disetujui.');
            }

            // Setujui reservasi
            $reservation->update(['status' => 'APPROVED']);

            // Otomatis tolak pengajuan PENDING lain yang bentrok di slot waktu yang sama
            Reservation::where('facility_id', $reservation->facility_id)
                ->where('id', '!=', $reservation->id)
                ->where('status', 'PENDING')
                ->where('start_time', '<', $reservation->end_time)
                ->where('end_time', '>', $reservation->start_time)
                ->update([
                    'status' => 'REJECTED',
                    'cancel_reason' => 'Ditolak otomatis oleh sistem karena slot waktu telah disetujui untuk pemohon lain.',
                ]);

            DB::commit();

            return back()->with('success', 'Reservasi berhasil disetujui.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Gagal menyetujui reservasi ID {$id}: " . $e->getMessage());

            return back()->with('error', 'Terjadi kesalahan sistem saat memproses persetujuan reservasi.');
        }
    }

    public function reject(Request $request, $id)
    {
        $validated = $request->validate([
            'cancel_reason' => 'required|string|max:1000',
        ]);

        $reservation = Reservation::findOrFail($id);

        if ($reservation->status !== 'PENDING') {
            return back()->with('error', 'Hanya reservasi berstatus PENDING yang dapat ditolak.');
        }

        $reservation->update([
            'status' => 'REJECTED',
            'cancel_reason' => $validated['cancel_reason'],
        ]);

        return back()->with('success', 'Reservasi berhasil ditolak.');
    }

    public function staffCancel(Request $request, $id)
    {
        $validated = $request->validate([
            'cancel_reason' => 'required|string|max:1000',
        ]);

        $reservation = Reservation::findOrFail($id);

        if ($reservation->status === 'PENDING') {
            return back()->with('error', 'Reservasi PENDING diproses melalui tombol Setujui / Tolak, bukan pembatalan.');
        }

        if ($reservation->status !== 'APPROVED') {
            return back()->with('error', 'Hanya reservasi yang sudah disetujui yang dapat dibatalkan.');
        }

        // Petugas hanya bisa membatalkan paling lambat 30 menit sebelum waktu pelaksanaan
        $startTime = Carbon::parse($reservation->start_time, config('app.timezone'));
        $cancelDeadline = $startTime->copy()->subMinutes(30);

        if (now(config('app.timezone'))->greaterThanOrEqualTo($cancelDeadline)) {
            return back()->with('error', 'Reservasi hanya dapat dibatalkan paling lambat 30 menit sebelum waktu pelaksanaan.');
        }

        $reservation->update([
            'status' => 'CANCELLED',
            'cancel_reason' => $validated['cancel_reason'],
        ]);

        return back()->with('success', 'Reservasi yang telah disetujui berhasil dibatalkan.');
    }
}