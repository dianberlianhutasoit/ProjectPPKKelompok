<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Reservation;
use Carbon\Carbon;

class ReservationController extends Controller
{
    /**
     * Membuat reservasi baru + Validasi Core Logic
     */
    public function store(Request $request)
    {
        // 1. Validasi Format Input
        $request->validate([
            'facility_id'      => 'required|exists:facilities,id',
            'reservation_date' => 'required|date_format:Y-m-d',
            'start_time'       => 'required|date_format:H:i',
            'end_time'         => 'required|date_format:H:i|after:start_time',
            'purpose'          => 'required|string',
        ]);

        // Gabungkan tanggal & jam menjadi format DateTime (Sesuai Migration Person 1)
        $startCarbon = Carbon::parse($request->reservation_date . ' ' . $request->start_time);
        $endCarbon   = Carbon::parse($request->reservation_date . ' ' . $request->end_time);

        // 2. Business Rule: Kelipatan 30 Menit
        if ($startCarbon->minute % 30 !== 0 || $endCarbon->minute % 30 !== 0) {
            return response()->json([
                'message' => 'Waktu mulai dan selesai harus kelipatan 30 menit (misal: 08:00, 08:30).'
            ], 422);
        }

        // 3. Business Rule: Jam Operasional (07:00 - 20:00)
        $opStart = Carbon::parse($request->reservation_date . ' 07:00');
        $opEnd   = Carbon::parse($request->reservation_date . ' 20:00');

        if ($startCarbon->lt($opStart) || $endCarbon->gt($opEnd)) {
            return response()->json([
                'message' => 'Reservasi hanya diperbolehkan pada jam operasional (07:00 - 20:00).'
            ], 422);
        }

        // 4. Business Rule: Cek Anti-Bentrok (Overlap Check)
        $isOverlap = Reservation::where('facility_id', $request->facility_id)
            ->whereIn('status', ['PENDING', 'APPROVED'])
            ->where(function ($query) use ($startCarbon, $endCarbon) {
                $query->where('start_time', '<', $endCarbon)
                      ->where('end_time', '>', $startCarbon);
            })
            ->exists();

        if ($isOverlap) {
            return response()->json([
                'message' => 'Fasilitas sudah dipesan pada rentang waktu tersebut.'
            ], 422);
        }

        // 5. Simpan ke Database
        $reservation = Reservation::create([
            'user_id'     => Auth::id() ?? 1, // Fallback ke User ID 1 jika testing tanpa login
            'facility_id' => $request->facility_id,
            'start_time'  => $startCarbon,
            'end_time'    => $endCarbon,
            'purpose'     => $request->purpose,
            'status'      => 'PENDING',
        ]);

        return response()->json([
            'message' => 'Reservasi berhasil dibuat.',
            'data'    => $reservation
        ], 201);
    }

    public function cancelByUser($id)
    {
        // Logika pembatalan oleh user
    }

    public function approveByPetugas($id)
    {
        // Logika persetujuan petugas
    }

    public function rejectByPetugas(Request $request, $id)
    {
        // Logika penolakan petugas
    }

    public function cancelByPetugas(Request $request, $id)
    {
        // Logika pembatalan oleh petugas
    }
}