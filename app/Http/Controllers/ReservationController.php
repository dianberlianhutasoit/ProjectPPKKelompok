<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReservationRequest;
use App\Models\Facility;
use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class ReservationController extends Controller
{
    public function create(Facility $facility)
    {
        if ($facility->status !== 'AVAILABLE') {
            return redirect()
                ->route('facilities.show', $facility)
                ->withErrors(['facility' => 'Fasilitas tidak tersedia untuk reservasi.']);
        }

        return view('reservations.create', compact('facility'));
    }

    public function store(ReservationRequest $request, Facility $facility)
    {
        if ($facility->status !== 'AVAILABLE') {
            return back()
                ->withErrors(['facility' => 'Fasilitas sedang tidak tersedia.'])
                ->withInput();
        }

        $validated = $request->validated();

        $start = Carbon::createFromFormat('Y-m-d H:i', $validated['date'].' '.$validated['start_time']);
        $end = Carbon::createFromFormat('Y-m-d H:i', $validated['date'].' '.$validated['end_time']);

        // Validasi kelipatan 30 menit
        if ($start->minute % 30 !== 0 || $end->minute % 30 !== 0) {
            return back()
                ->withErrors(['start_time' => 'Jam reservasi harus menggunakan interval 30 menit.'])
                ->withInput();
        }

        if ($end->lessThanOrEqualTo($start)) {
            return back()
                ->withErrors(['end_time' => 'Jam selesai harus setelah jam mulai.'])
                ->withInput();
        }

        if ($validated['participants'] > $facility->capacity) {
            return back()
                ->withErrors(['participants' => 'Jumlah peserta melebihi kapasitas fasilitas.'])
                ->withInput();
        }

        // Anti-overlap: tolak jika bentrok dengan reservasi PENDING/APPROVED.
        $overlap = Reservation::where('facility_id', $facility->id)
            ->whereIn('status', ['PENDING', 'APPROVED'])
            ->where('start_time', '<', $end)
            ->where('end_time', '>', $start)
            ->exists();

        if ($overlap) {
            return back()
                ->withErrors(['start_time' => 'Jadwal tersebut sudah memiliki reservasi.'])
                ->withInput();
        }

        Reservation::create([
            'user_id' => Auth::id(),
            'facility_id' => $facility->id,
            'participants' => $validated['participants'],
            'start_time' => $start,
            'end_time' => $end,
            'purpose' => $validated['purpose'],
            'status' => 'PENDING',
        ]);

        return redirect()
            ->route('reservations.index')
            ->with('success', 'Reservasi berhasil diajukan dan menunggu persetujuan petugas.');
    }

    public function index()
    {
        $reservations = Reservation::with('facility')
            ->where('user_id', Auth::id())
            ->orderByDesc('start_time')
            ->get();

        return view('reservations.index', compact('reservations'));
    }

    public function cancel(Reservation $reservation)
    {
        if ($reservation->user_id !== Auth::id()) {
            abort(403, 'Unauthorized Access');
        }

        if ($reservation->status !== 'PENDING') {
            return back()->withErrors([
                'reservation' => 'Hanya reservasi dengan status PENDING yang dapat dibatalkan.',
            ]);
        }

        // USER hanya boleh cancel paling lambat 2 jam sebelum start_time.
        if (Carbon::now()->greaterThan(Carbon::parse($reservation->start_time)->subHours(2))) {
            return back()->withErrors([
                'reservation' => 'Reservasi hanya dapat dibatalkan paling lambat 2 jam sebelum waktu penggunaan.',
            ]);
        }

        $reservation->update([
            'status' => 'CANCELLED',
            'cancel_reason' => 'Dibatalkan oleh pengguna.',
        ]);

        return back()->with('success', 'Reservasi berhasil dibatalkan.');
    }
}
