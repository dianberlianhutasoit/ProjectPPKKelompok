<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request; 
use App\Models\Reservation;
use App\Http\Requests\ReservationRequest; 
use Carbon\Carbon;

class ReservationController extends Controller
{
    /**
     * Membuat reservasi baru + Validasi Core Logic
     */
    public function store(ReservationRequest $request)
    {

        // 1. Format waktu
        $startCarbon = Carbon::parse($request->reservation_date . ' ' . $request->start_time);
        $endCarbon   = Carbon::parse($request->reservation_date . ' ' . $request->end_time);

        // 2. Cek Anti-Bentrok (Overlap Check)
        $isOverlap = Reservation::where('facility_id', $request->facility_id)
            ->whereIn('status', ['PENDING', 'APPROVED'])
            ->where(function ($query) use ($startCarbon, $endCarbon) {
                $query->where('start_time', '<', $endCarbon)
                      ->where('end_time', '>', $startCarbon);
            })
            ->exists();

        if ($isOverlap) {
            return response()->json([
                'message' => 'Maaf, fasilitas sudah dipesan pada rentang waktu tersebut.'
            ], 422);
        }

        // 3. Simpan ke Database
        $reservation = Reservation::create([
            'user_id'     => Auth::id() ?? 1, // Catatan: Nanti hapus '?? 1' jika sistem login sudah siap
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
        $reservation = Reservation::findOrFail($id);

        // 1. Validasi Keamanan: Pastikan yang membatalkan adalah pemilik reservasi
        if ($reservation->user_id !== Auth::id()) {
            return response()->json(['message' => 'Anda tidak berhak membatalkan reservasi ini.'], 403);
        }

        // 2. Business Rule: Hanya reservasi yang masih PENDING (belum di-approve) yang boleh dibatalkan
        if ($reservation->status !== 'PENDING') {
            return response()->json([
                'message' => 'Reservasi tidak dapat dibatalkan karena sudah disetujui atau diproses oleh petugas.'
            ], 400);
        }

        // Ubah status menjadi CANCELLED
        $reservation->update(['status' => 'CANCELLED']);

        return response()->json(['message' => 'Reservasi Anda berhasil dibatalkan.']);
    }

    public function approveByPetugas($id)
    {
        // Logika persetujuan petugas
        $reservation = Reservation::findOrFail($id);
        
        $reservation->update(['status' => 'APPROVED']);

        return response()->json(['message' => 'Reservasi berhasil disetujui.']);
    }

    public function rejectByPetugas(Request $request, $id)
    {
        // Logika penolakan petugas (butuh input alasan)
        $request->validate([
            'alasan' => 'required|string|max:255'
        ], [
            'alasan.required' => 'Alasan penolakan wajib diisi.'
        ]);

        $reservation = Reservation::findOrFail($id);
        
        $reservation->update([
            'status' => 'REJECTED',
            'alasan' => $request->alasan 
        ]);

        return response()->json(['message' => 'Reservasi berhasil ditolak.']);
    }

    public function cancelByPetugas(Request $request, $id)
    {
        // Logika pembatalan oleh petugas (butuh input alasan)
        $request->validate([
            'alasan' => 'required|string|max:255'
        ], [
            'alasan.required' => 'Alasan pembatalan mendesak wajib diisi.'
        ]);

        $reservation = Reservation::findOrFail($id);
        
        $reservation->update([
            'status' => 'CANCELLED',
            'alasan' => $request->alasan 
        ]);

        return response()->json(['message' => 'Reservasi berhasil dibatalkan oleh Petugas.']);
    }
}