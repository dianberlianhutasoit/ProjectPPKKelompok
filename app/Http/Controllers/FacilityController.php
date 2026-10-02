<?php

namespace App\Http\Controllers;

use App\Models\Facility;
use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FacilityController extends Controller
{
    public function index(Request $request)
    {
        $query = Facility::query();

        // INACTIVE disembunyikan dari non-ADMIN.
        if (! Auth::check() || Auth::user()->role !== 'ADMIN') {
            $query->where('status', '!=', 'INACTIVE');
        }

        if ($request->filled('type')) {
            $query->where('type', $request->string('type'));
        }
        if ($request->filled('location')) {
            $query->where('location', 'like', '%'.$request->string('location').'%');
        }
        if ($request->filled('min_capacity')) {
            $query->where('capacity', '>=', (int) $request->input('min_capacity'));
        }

        $facilities = $query->orderBy('name')->get();

        $types = Facility::select('type')->distinct()->orderBy('type')->pluck('type');

        return view('facilities.index', [
            'facilities' => $facilities,
            'types' => $types,
            'filters' => $request->only(['type', 'location', 'min_capacity']),
        ]);
    }

    public function create()
    {
        return view('facilities.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1',
            'description' => 'nullable|string',
            'status' => 'required|in:AVAILABLE,MAINTENANCE,INACTIVE',
        ]);

        Facility::create($validated);

        return redirect()->route('facilities.index')->with('success', 'Fasilitas berhasil ditambahkan.');
    }

    public function show(Request $request, Facility $facility)
    {
        // INACTIVE dianggap tidak ada untuk non-ADMIN.
        if ($facility->status === 'INACTIVE' && (! Auth::check() || Auth::user()->role !== 'ADMIN')) {
            abort(404);
        }

        $selectedDate = $request->input('date', now()->toDateString());

        // Slot diblokir oleh reservasi PENDING/APPROVED.
        $reservations = Reservation::where('facility_id', $facility->id)
            ->whereIn('status', ['PENDING', 'APPROVED'])
            ->whereDate('start_time', $selectedDate)
            ->orderBy('start_time')
            ->get();

        $slots = [];

        // Jam operasional 07:00-20:00.
        $start = Carbon::createFromFormat('Y-m-d H:i', $selectedDate.' 07:00');
        $end = Carbon::createFromFormat('Y-m-d H:i', $selectedDate.' 20:00');

        while ($start->lessThan($end)) {
            $slotStart = $start->copy();
            $slotEnd = $start->copy()->addMinutes(30);

            $status = 'AVAILABLE';

            // MAINTENANCE: semua slot dianggap maintenance.
            if ($facility->status === 'MAINTENANCE') {
                $status = 'MAINTENANCE';
            } else {
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

        return view('facilities.show', compact('facility', 'selectedDate', 'slots'));
    }

    // Nonaktifkan saja (INACTIVE) agar riwayat pinjam/lapor tetap terjaga.
    public function destroy(Facility $facility)
    {
        $facility->update(['status' => 'INACTIVE']);

        return redirect()->route('facilities.index')->with('success', 'Fasilitas dinonaktifkan (INACTIVE).');
    }

    public function edit(Facility $facility)
    {
        $types = Facility::select('type')->distinct()->pluck('type');

        return view('facilities.edit', compact('facility', 'types'));
    }

    public function update(Request $request, Facility $facility)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1',
            'description' => 'nullable|string',
            'status' => 'required|in:AVAILABLE,MAINTENANCE,INACTIVE',
        ]);

        $facility->update($validated);

        return redirect()->route('facilities.index')
            ->with('success', 'Fasilitas '.$facility->name.' berhasil diperbarui.');
    }
}
