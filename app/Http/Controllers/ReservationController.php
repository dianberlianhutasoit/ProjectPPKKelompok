<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReservationRequest;
use App\Models\Facility;
use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReservationController extends Controller
{
    // Form pengajuan reservasi khusus USER
    public function create(Facility $facility)
    {
        if ($facility->status !== 'AVAILABLE') {
            return redirect()
                ->route('facilities.show', $facility)
                ->withErrors(['facility' => 'Fasilitas tidak tersedia untuk reservasi.']);
        }

        return view('reservations.create', compact('facility'));
    }

<<<<<<< HEAD
    // Simpan pengajuan reservasi
=======
    // Simpan pengajuan reservasi + Core Logic Pengecekan Bentrok
>>>>>>> 6f631731ed7493d53d9cad3b55bee88549bdbb15
    public function store(ReservationRequest $request, Facility $facility)
    {
        if ($facility->status !== 'AVAILABLE') {
            return back()
                ->withErrors(['facility' => 'Fasilitas sedang tidak tersedia.'])
                ->withInput();
        }

        $validated = $request->validated();

<<<<<<< HEAD
        // Pastikan jam mulai dan selesai benar-benar kelipatan 30 menit
        $start = Carbon::createFromFormat(
            'Y-m-d H:i',
            $validated['date'] . ' ' . $validated['start_time']
        );

        $end = Carbon::createFromFormat(
            'Y-m-d H:i',
            $validated['date'] . ' ' . $validated['end_time']
        );

        if ($start->minute % 30 !== 0 || $end->minute % 30 !== 0) {
            return back()
                ->withErrors([
                    'start_time' => 'Jam reservasi harus menggunakan interval 30 menit.'
                ])
=======
        $start = Carbon::createFromFormat('Y-m-d H:i', $validated['date'] . ' ' . $validated['start_time']);
        $end   = Carbon::createFromFormat('Y-m-d H:i', $validated['date'] . ' ' . $validated['end_time']);

        // Validasi kelipatan 30 menit
        if ($start->minute % 30 !== 0 || $end->minute % 30 !== 0) {
            return back()
                ->withErrors(['start_time' => 'Jam reservasi harus menggunakan interval 30 menit.'])
>>>>>>> 6f631731ed7493d53d9cad3b55bee88549bdbb15
                ->withInput();
        }

        if ($end->lessThanOrEqualTo($start)) {
            return back()
<<<<<<< HEAD
                ->withErrors([
                    'end_time' => 'Jam selesai harus setelah jam mulai.'
                ])
                ->withInput();
        }

        // Cek apakah jumlah peserta melebihi kapasitas fasilitas
        if ($validated['participants'] > $facility->capacity) {
            return back()
                ->withErrors([
                    'participants' => 'Jumlah peserta melebihi kapasitas fasilitas.'
                ])
                ->withInput();
        }

        // Cek bentrok jadwal
=======
                ->withErrors(['end_time' => 'Jam selesai harus setelah jam mulai.'])
                ->withInput();
        }

        // Cek Kapasitas Fasilitas
        if ($validated['participants'] > $facility->capacity) {
            return back()
                ->withErrors(['participants' => 'Jumlah peserta melebihi kapasitas fasilitas.'])
                ->withInput();
        }

        // Cek Anti-Bentrok (Overlap)
>>>>>>> 6f631731ed7493d53d9cad3b55bee88549bdbb15
        $overlap = Reservation::where('facility_id', $facility->id)
            ->whereIn('status', ['PENDING', 'APPROVED'])
            ->where('start_time', '<', $end)
            ->where('end_time', '>', $start)
            ->exists();

        if ($overlap) {
            return back()
<<<<<<< HEAD
                ->withErrors([
                    'start_time' => 'Jadwal tersebut sudah memiliki reservasi.'
                ])
=======
                ->withErrors(['start_time' => 'Jadwal tersebut sudah memiliki reservasi.'])
>>>>>>> 6f631731ed7493d53d9cad3b55bee88549bdbb15
                ->withInput();
        }

        Reservation::create([
<<<<<<< HEAD
            'user_id' => Auth::id(),
            'facility_id' => $facility->id,
            'participants' => $validated['participants'],
            'start_time' => $start,
            'end_time' => $end,
            'purpose' => $validated['purpose'],
            'status' => 'PENDING',
=======
            'user_id'         => Auth::id(),
            'facility_id'     => $facility->id,
            'identity_number' => $validated['identity_number'],
            'participants'    => $validated['participants'],
            'start_time'      => $start,
            'end_time'        => $end,
            'purpose'         => $validated['purpose'],
            'status'          => 'PENDING',
>>>>>>> 6f631731ed7493d53d9cad3b55bee88549bdbb15
        ]);

        return redirect()
            ->route('reservations.index')
            ->with('success', 'Reservasi berhasil diajukan dan menunggu persetujuan petugas.');
    }

    // Riwayat reservasi milik USER
    public function index()
    {
        $reservations = Reservation::with('facility')
            ->where('user_id', Auth::id())
            ->orderByDesc('start_time')
            ->get();

        return view('reservations.index', compact('reservations'));
    }

<<<<<<< HEAD
    // USER hanya boleh membatalkan reservasi yang masih PENDING
=======
    // USER membatalkan reservasi (Hanya PENDING)
>>>>>>> 6f631731ed7493d53d9cad3b55bee88549bdbb15
    public function cancel(Reservation $reservation)
    {
        if ($reservation->user_id !== Auth::id()) {
            abort(403, 'Unauthorized Access');
        }

        if ($reservation->status !== 'PENDING') {
            return back()->withErrors([
                'reservation' => 'Hanya reservasi dengan status PENDING yang dapat dibatalkan.'
            ]);
        }

        $reservation->update([
<<<<<<< HEAD
            'status' => 'CANCELLED',
=======
            'status'        => 'CANCELLED',
>>>>>>> 6f631731ed7493d53d9cad3b55bee88549bdbb15
            'cancel_reason' => 'Dibatalkan oleh pengguna.',
        ]);

        return back()->with('success', 'Reservasi berhasil dibatalkan.');
    }

<<<<<<< HEAD
    // Halaman pengajuan reservasi untuk STAFF
=======
    // Pengelolaan reservasi oleh STAFF/ADMIN
>>>>>>> 6f631731ed7493d53d9cad3b55bee88549bdbb15
    public function staffIndex()
    {
        $reservations = Reservation::with(['user', 'facility'])
            ->orderByDesc('created_at')
            ->get();

        return view('staff.reservations.index', compact('reservations'));
    }

    // STAFF menyetujui reservasi
    public function approve(Reservation $reservation)
    {
        if ($reservation->status !== 'PENDING') {
            return back()->withErrors([
                'reservation' => 'Hanya reservasi PENDING yang dapat disetujui.'
            ]);
        }

<<<<<<< HEAD
        // Cek kembali bentrok sebelum approve
=======
>>>>>>> 6f631731ed7493d53d9cad3b55bee88549bdbb15
        $overlap = Reservation::where('facility_id', $reservation->facility_id)
            ->where('id', '!=', $reservation->id)
            ->where('status', 'APPROVED')
            ->where('start_time', '<', $reservation->end_time)
            ->where('end_time', '>', $reservation->start_time)
            ->exists();

        if ($overlap) {
            return back()->withErrors([
                'reservation' => 'Reservasi tidak dapat disetujui karena jadwal sudah bentrok.'
            ]);
        }

<<<<<<< HEAD
        $reservation->update([
            'status' => 'APPROVED',
        ]);
=======
        $reservation->update(['status' => 'APPROVED']);
>>>>>>> 6f631731ed7493d53d9cad3b55bee88549bdbb15

        return back()->with('success', 'Reservasi berhasil disetujui.');
    }

    // STAFF menolak reservasi
    public function reject(Request $request, Reservation $reservation)
    {
        $validated = $request->validate([
            'cancel_reason' => 'required|string|max:1000',
        ]);

        if ($reservation->status !== 'PENDING') {
            return back()->withErrors([
                'reservation' => 'Hanya reservasi PENDING yang dapat ditolak.'
            ]);
        }

        $reservation->update([
<<<<<<< HEAD
            'status' => 'REJECTED',
=======
            'status'        => 'REJECTED',
>>>>>>> 6f631731ed7493d53d9cad3b55bee88549bdbb15
            'cancel_reason' => $validated['cancel_reason'],
        ]);

        return back()->with('success', 'Reservasi berhasil ditolak.');
    }

<<<<<<< HEAD
    // STAFF membatalkan reservasi yang sudah disetujui
=======
    // STAFF membatalkan reservasi
>>>>>>> 6f631731ed7493d53d9cad3b55bee88549bdbb15
    public function staffCancel(Request $request, Reservation $reservation)
    {
        $validated = $request->validate([
            'cancel_reason' => 'required|string|max:1000',
        ]);

        if (!in_array($reservation->status, ['PENDING', 'APPROVED'])) {
            return back()->withErrors([
                'reservation' => 'Reservasi ini tidak dapat dibatalkan oleh petugas.'
            ]);
        }

        $reservation->update([
<<<<<<< HEAD
            'status' => 'CANCELLED',
=======
            'status'        => 'CANCELLED',
>>>>>>> 6f631731ed7493d53d9cad3b55bee88549bdbb15
            'cancel_reason' => $validated['cancel_reason'],
        ]);

        return back()->with('success', 'Reservasi berhasil dibatalkan oleh petugas.');
    }
}