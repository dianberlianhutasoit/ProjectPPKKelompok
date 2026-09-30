<?php

namespace App\Http\Controllers;

use App\Models\Facility;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    // USER: daftar laporan milik sendiri
    public function index()
    {
        $reports = Report::with('facility')
            ->where('user_id', Auth::id())
            ->orderByDesc('created_at')
            ->get();

        return response()->json($reports);
    }

    // USER: context form (daftar fasilitas untuk dropdown frontend)
    public function create()
    {
        $facilities = Facility::orderBy('name')->get(['id', 'name']);

        return response()->json($facilities);
    }

    // USER: simpan laporan, user_id selalu dari Auth
    public function store(Request $request)
    {
        $validated = $request->validate([
            'facility_id' => 'required|exists:facilities,id',
            'category' => 'required|string|max:255',
            'description' => 'required|string',
            'photo' => 'nullable|image|max:2048',
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('reports', 'public');
        }

        $report = Report::create([
            'user_id' => Auth::id(),
            'facility_id' => $validated['facility_id'],
            'category' => $validated['category'],
            'description' => $validated['description'],
            'photo' => $photoPath,
            'status' => 'NEW',
        ]);

        return redirect()->route('reports.index')->with('success', 'Laporan berhasil dibuat.');
    }

    // USER (milik sendiri) / STAFF (semua): detail satu laporan
    public function show(Report $report)
    {
        if (strtoupper(Auth::user()->role) === 'USER' && $report->user_id !== Auth::id()) {
            abort(403, 'Unauthorized Access');
        }

        $report->load(['user', 'facility']);

        return response()->json($report);
    }

    // STAFF: daftar semua laporan
    public function staffIndex()
    {
        $reports = Report::with(['user', 'facility'])
            ->orderByDesc('created_at')
            ->get();

        return response()->json($reports);
    }

    // STAFF: perbarui status + resolution_note
    public function update(Request $request, Report $report)
    {
        $validated = $request->validate([
            'status' => 'required|in:NEW,PROCESSING,COMPLETED,REJECTED',
            'resolution_note' => 'nullable|string|max:2000',
        ]);

        $report->update($validated);

        return back()->with('success', 'Status laporan berhasil diperbarui.');
    }
}
