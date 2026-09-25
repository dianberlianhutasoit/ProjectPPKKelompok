<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReservationApprovalController extends Controller
{
    public function index(Request $request)
    {
        $filters = [
            'status'      => $request->query('status', 'PENDING'),
            'facility_id' => $request->query('facility_id'),
            'sort_by'     => $request->query('sort_by', 'created_at'),
            'sort_order'  => $request->query('sort_order', 'asc'),
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

            $reservation = Reservation::where('id', $id)
                ->where('status', 'PENDING')
                ->lockForUpdate()
                ->firstOrFail();

            $overlap = Reservation::where('facility_id', $reservation->facility_id)
                ->where('id', '!=', $reservation->id)
                ->where('status', 'APPROVED')
                ->where('start_time', '<', $reservation->end_time)
                ->where('end_time', '>', $reservation->start_time)
                ->exists();

            if ($overlap) {
                DB::rollBack();
                return back()->withErrors([
                    'reservation' => 'Reservasi tidak dapat disetujui karena jadwal sudah bentrok.'
                ]);
            }

            $reservation->update(['status' => 'APPROVED']);

            DB::commit();

            return back()->with('success', 'Reservasi berhasil disetujui.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors([
                'reservation' => 'Terjadi kesalahan saat memproses persetujuan.'
            ]);
        }
    }

    public function reject(Request $request, $id)
    {
        $validated = $request->validate([
            'cancel_reason' => 'required|string|max:1000',
        ]);

        $reservation = Reservation::findOrFail($id);

        if ($reservation->status !== 'PENDING') {
            return back()->withErrors([
                'reservation' => 'Hanya reservasi PENDING yang dapat ditolak.'
            ]);
        }

        $reservation->update([
            'status'        => 'REJECTED',
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

        if (!in_array($reservation->status, ['PENDING', 'APPROVED'])) {
            return back()->withErrors([
                'reservation' => 'Reservasi ini tidak dapat dibatalkan oleh petugas.'
            ]);
        }

        $reservation->update([
            'status'        => 'CANCELLED',
            'cancel_reason' => $validated['cancel_reason'],
        ]);

        return back()->with('success', 'Reservasi berhasil dibatalkan oleh petugas.');
    }
}