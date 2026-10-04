<?php

namespace App\Http\Controllers;

use App\Models\Facility;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        $reports = Report::with('facility')
            ->where('user_id', Auth::id())
            ->orderByDesc('created_at')
            ->get();

        return view('reports.index', compact('reports'));
    }

    public function create(Request $request)
    {
        $facilities = Facility::orderBy('name')->get();

        $selectedFacility = null;

        if ($request->filled('facility_id')) {
            $selectedFacility = Facility::find($request->facility_id);
        }

        return view('reports.create', compact('facilities', 'selectedFacility'));
    }

    // Set the report owner from the signed-in user.
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

        Report::create([
            'user_id' => Auth::id(),
            'facility_id' => $validated['facility_id'],
            'category' => $validated['category'],
            'description' => $validated['description'],
            'photo' => $photoPath,
            'status' => 'NEW',
        ]);

        return redirect()->route('reports.index')->with('success', 'Laporan berhasil dibuat.');
    }

    // Users can view only their own reports.
    public function show(Report $report)
    {
        if (strtoupper(Auth::user()->role) === 'USER' && $report->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        $report->load(['user', 'facility']);

        return view('reports.show', compact('report'));
    }

    public function staffIndex()
    {
        $reports = Report::with(['user', 'facility'])
            ->orderByDesc('created_at')
            ->get();

        return view('staff.reports.index', compact('reports'));
    }

    public function staffShow(Report $report)
    {
        $report->load(['user', 'facility']);

        return view('staff.reports.show', compact('report'));
    }

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

            // Leave inactive facilities unchanged.
            if (! $facility || $facility->status === 'INACTIVE') {
                return;
            }

            if ($validated['status'] === 'PROCESSING') {
                if ($facility->status === 'AVAILABLE') {
                    $facility->update(['status' => 'MAINTENANCE']);
                }

                return;
            }

            // Restore availability when no other report is processing.
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
    // Ekspor data laporan kerusakan ke CSV
    public function exportCsv()
    {
        $fileName = 'rekap_laporan_kerusakan_' . date('Y-m-d_H-i') . '.csv';
        $reports = Report::with(['facility', 'user'])->get();

        $headers = [
            "Content-type"        => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function () use ($reports) {
            $file = fopen('php://output', 'w');
            fputs($file, "\xEF\xBB\xBF"); // UTF-8 BOM untuk Excel

            fputcsv($file, ['ID Laporan', 'Nama Fasilitas', 'Nama Pelapor', 'Judul Kerusakan', 'Deskripsi', 'Status', 'Tanggal Lapor']);

            foreach ($reports as $report) {
                fputcsv($file, [
                    $report->id,
                    $report->facility->name ?? '-',
                    $report->user->name ?? '-',
                    $report->title,
                    $report->description,
                    $report->status,
                    $report->created_at->format('d-m-Y H:i'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
