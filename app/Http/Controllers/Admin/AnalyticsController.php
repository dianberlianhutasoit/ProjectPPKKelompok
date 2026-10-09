<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Facility;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AnalyticsController extends Controller
{
    /**
     * Tampilan Halaman Rekap Okupansi & Frekuensi Kerusakan Admin
     */
    public function index()
    {
        $facilities = Facility::withCount([
            'reservations as total_reservations' => function ($query) {
                $query->where('status', 'APPROVED');
            },
            'reports as total_reports'
        ])
        ->orderByDesc('total_reservations')
        ->get();

        return view('admin.analytics.index', compact('facilities'));
    }

    /**
     * Ekspor Rekap Okupansi & Frekuensi Kerusakan ke CSV
     */
    public function exportCsv(): StreamedResponse
    {
        $fileName = 'rekap-okupansi-dan-kerusakan-' . date('Y-m-d-H-i-s') . '.csv';

        $facilities = Facility::withCount([
            'reservations as total_reservations' => function ($query) {
                $query->where('status', 'APPROVED');
            },
            'reports as total_reports'
        ])
        ->orderByDesc('total_reservations')
        ->get();

        $headers = [
            "Content-type"        => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = [
            'ID Fasilitas',
            'Nama Fasilitas',
            'Lokasi / Gedung',
            'Kapasitas',
            'Status Saat Ini',
            'Total Reservasi Disetujui (Okupansi)',
            'Frekuensi Laporan Kerusakan',
            'Tingkat Kerusakan (%)'
        ];

        $callback = function () use ($facilities, $columns) {
            $file = fopen('php://output', 'w');
            
            // BOM UTF-8 agar karakter spesial & format tabel rapi di Microsoft Excel
            fputs($file, "\xEF\xBB\xBF");
            fputcsv($file, $columns);

            foreach ($facilities as $facility) {
                $damageRatio = $facility->total_reservations > 0 
                    ? round(($facility->total_reports / $facility->total_reservations) * 100, 2) . '%' 
                    : '0%';

                fputcsv($file, [
                    $facility->id,
                    $facility->name,
                    $facility->location,
                    $facility->capacity,
                    $facility->status,
                    $facility->total_reservations,
                    $facility->total_reports,
                    $damageRatio
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}