<?php

namespace App\Http\Controllers;

use App\Models\Facility;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    // USER: daftar laporan milik sendiri
    public function index()
    {
        $reports = Report::with('facility')
            ->where('user_id', Auth::id())
            ->orderByDesc('created_at')
            ->get();

        return view('reports.index', compact('reports'));
    }

    // USER: context form (daftar fasilitas untuk dropdown frontend)
    public function create(Request $request)
    {
       $facilities = Facility::orderBy('name')->get();

        $selectedFacility = null;

        if ($request->filled('facility_id')) {
            $selectedFacility = Facility::find($request->facility_id);
        }

        return view('reports.create', compact('facilities', 'selectedFacility'));
    }

    // USER: simpan laporan, user_id selalu dari Auth
    public function store(Request $request)
    {
        $validated = $request->validate([
            'facility_id' => 'required|exists:facilities,id',
            'category' => 'required|string|max:255',
            'description' => 'required|string',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
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

        return view('reports.show', compact('report'));
    }

    // STAFF: daftar semua laporan
    public function staffIndex()
    {
        $reports = Report::with(['user', 'facility'])
            ->orderByDesc('created_at')
            ->get();

        return view('staff.reports.index', compact('reports'));
    }

    // STAFF: detail laporan untuk dikelola
    public function staffShow(Report $report)
    {
        $report->load(['user', 'facility']);

        return view(
            'staff.reports.show',
            compact('report')
        );
    }

    // STAFF: perbarui status + resolution_note (+ sinkron status fasilitas)
    public function update(Request $request, Report $report)
    {
        $validated = $request->validate([
            'status' => 'required|in:NEW,PROCESSING,COMPLETED,REJECTED',
            'resolution_note' => 'nullable|string|max:2000',
        ]);

        if (in_array($validated['status'], ['COMPLETED', 'REJECTED'], true) && blank($validated['resolution_note'] ?? null)) {
            return back()
                ->withErrors(['resolution_note' => 'Catatan penyelesaian wajib diisi untuk status COMPLETED atau REJECTED.'])
                ->withInput();
        }

        DB::transaction(function () use ($report, $validated) {
            $report->update($validated);

            $facility = $report->facility;

            // INACTIVE permanen: jangan ubah status fasilitas.
            if (! $facility || $facility->status === 'INACTIVE') {
                return;
            }

            if ($validated['status'] === 'PROCESSING') {
                if ($facility->status === 'AVAILABLE') {
                    $facility->update(['status' => 'MAINTENANCE']);
                }

                return;
            }

            // COMPLETED/REJECTED: kembalikan AVAILABLE hanya jika
            // tidak ada laporan PROCESSING lain untuk fasilitas yang sama.
            if ($facility->status === 'MAINTENANCE') {
                $otherProcessing = Report::where('facility_id', $facility->id)
                    ->where('id', '!=', $report->id)
                    ->where('status', 'PROCESSING')
                    ->exists();

                if (! $otherProcessing) {
                    $facility->update(['status' => 'AVAILABLE']);
                }
            }
        });

        return back()->with('success', 'Status laporan berhasil diperbarui.');
    }
}