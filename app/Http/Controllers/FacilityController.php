<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Carbon\Carbon;
use App\Models\Facility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FacilityController extends Controller
{
    // Halaman daftar fasilitas, bisa dibuka tanpa login + ada filter pencarian
    public function index(Request $request)
    {
        $query = Facility::query();

        // Yang INACTIVE cuma boleh dilihat admin, yang lain disembunyikan
        if (!Auth::check() || Auth::user()->role !== 'ADMIN') {
            $query->where('status', '!=', 'INACTIVE');
        }

        // Filter pencarian: tipe, lokasi, kapasitas minimal
        if ($request->filled('type')) {
            $query->where('type', $request->string('type'));
        }
        if ($request->filled('location')) {
            $query->where('location', 'like', '%' . $request->string('location') . '%');
        }
        if ($request->filled('min_capacity')) {
            $query->where('capacity', '>=', (int) $request->input('min_capacity'));
        }

        $facilities = $query->orderBy('name')->get();

        // Buat isi dropdown tipe di form filter
        $types = Facility::select('type')->distinct()->orderBy('type')->pluck('type');

        return view('facilities.index', [
            'facilities' => $facilities,
            'types' => $types,
            'filters' => $request->only(['type', 'location', 'min_capacity']),
        ]);
    }

    // Admin: form tambah fasilitas
    public function create()
    {
        return view('facilities.create');
    }

    // Admin: simpan fasilitas baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:100',
            'location' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1',
            'description' => 'nullable|string',
            'status' => 'required|in:AVAILABLE,MAINTENANCE,INACTIVE',
        ]);

        Facility::create($validated);

        return redirect()->route('facilities.index')->with('success', 'Fasilitas berhasil ditambahkan.');
    }

    // Halaman detail fasilitas + ketersediaan slot
    public function show(Request $request, Facility $facility)
    {
        // Kalau INACTIVE dan bukan admin, anggap tidak ada
        if ($facility->status === 'INACTIVE' && (!Auth::check() || Auth::user()->role !== 'ADMIN')) {
            abort(404);
        }

        // Tanggal yang dipilih
        $selectedDate = $request->input('date', now()->toDateString());

        // Ambil reservasi yang masih memblokir slot
        $reservations = Reservation::where('facility_id', $facility->id)
            ->whereIn('status', ['PENDING', 'APPROVED'])
            ->whereDate('start_time', $selectedDate)
            ->orderBy('start_time')
            ->get();

        $slots = [];

        // Jam operasional 07:00 - 20:00
        $start = Carbon::createFromFormat(
            'Y-m-d H:i',
            $selectedDate . ' 07:00'
        );

        $end = Carbon::createFromFormat(
            'Y-m-d H:i',
            $selectedDate . ' 20:00'
        );

        while ($start->lessThan($end)) {

            $slotStart = $start->copy();
            $slotEnd = $start->copy()->addMinutes(30);

            // Default slot tersedia
            $status = 'AVAILABLE';

            // Kalau fasilitas sedang maintenance,
            // semua slot dianggap maintenance
            if ($facility->status === 'MAINTENANCE') {

                $status = 'MAINTENANCE';

            } else {

                // Cek apakah slot bentrok dengan reservasi
                $reserved = $reservations->contains(function ($reservation) use ($slotStart, $slotEnd) {

                    $reservationStart = Carbon::parse($reservation->start_time);
                    $reservationEnd = Carbon::parse($reservation->end_time);

                    return $reservationStart->lt($slotEnd)
                        && $reservationEnd->gt($slotStart);
                });

                if ($reserved) {
                    $status = 'RESERVED';
                }
            }

            $slots[] = [
                'start' => $slotStart->format('H:i'),
                'end' => $slotEnd->format('H:i'),
                'status' => $status,
            ];

            $start->addMinutes(30);
        }

        return view('facilities.show', compact(
            'facility',
            'selectedDate',
            'slots'
        ));
    }

    // Admin: nonaktifkan aja (biar riwayat pinjam/lapor tidak ikut hilang)
    public function destroy(Facility $facility)
    {
        $facility->update(['status' => 'INACTIVE']);

        return redirect()->route('facilities.index')->with('success', 'Fasilitas dinonaktifkan (INACTIVE).');
    }
/**
 * Menampilkan form edit fasilitas (Admin).
 */
public function edit(Facility $facility)
{
    // Mengambil tipe fasilitas unik dari DB untuk pilihan dropdown
    $types = Facility::select('type')->distinct()->pluck('type');

    return view('facilities.edit', compact('facility', 'types'));
}

/**
 * Memproses pembaruan data fasilitas di database.
 */
public function update(Request $request, Facility $facility)
{
    $validated = $request->validate([
        'name'        => 'required|string|max:255',
        'type'        => 'required|string|max:255',
        'location'    => 'required|string|max:255',
        'capacity'    => 'required|integer|min:1',
        'description' => 'nullable|string',
        'status'      => 'required|in:AVAILABLE,MAINTENANCE,INACTIVE',
    ]);

    $facility->update($validated);

    return redirect()->route('facilities.index')
        ->with('success', 'Fasilitas ' . $facility->name . ' berhasil diperbarui.');
}
}
