<?php

namespace App\Http\Controllers;

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

    // Halaman detail fasilitas, isinya info umum aja
    public function show(Facility $facility)
    {
        // Kalau INACTIVE dan bukan admin, anggap tidak ada
        if ($facility->status === 'INACTIVE' && (!Auth::check() || Auth::user()->role !== 'ADMIN')) {
            abort(404);
        }

        return view('facilities.show', compact('facility'));
    }

    // Admin: form edit fasilitas
    public function edit(Facility $facility)
    {
        return view('facilities.edit', compact('facility'));
    }

    // Admin: simpan hasil editan
    public function update(Request $request, Facility $facility)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:100',
            'location' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1',
            'description' => 'nullable|string',
            'status' => 'required|in:AVAILABLE,MAINTENANCE,INACTIVE',
        ]);

        $facility->update($validated);

        return redirect()->route('facilities.index')->with('success', 'Fasilitas berhasil diperbarui.');
    }

    // Admin: nonaktifkan aja (biar riwayat pinjam/lapor tidak ikut hilang)
    public function destroy(Facility $facility)
    {
        $facility->update(['status' => 'INACTIVE']);

        return redirect()->route('facilities.index')->with('success', 'Fasilitas dinonaktifkan (INACTIVE).');
    }
}
