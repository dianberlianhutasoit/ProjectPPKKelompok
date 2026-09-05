<?php

namespace App\Http\Controllers;

use App\Models\Facility;
use Illuminate\Http\Request;

class FacilityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Mengambil seluruh data fasilitas dari database
        $facilities = Facility::all();
        return $facilities;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi input fasilitas
        $request->validate([
            'name' => 'required',
            'type' => 'required',
            'location' => 'required',
            'capacity' => 'required|integer',
        ]);

        // Simpan fasilitas baru
        Facility::create([
            'name' => $request->name,
            'type' => $request->type,
            'location' => $request->location,
            'capacity' => $request->capacity,
            'description' => $request->description,
            'status' => 'AVAILABLE',
        ]);

        return "Facility created";
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function __construct()
    {
        // Membatasi akses Facility hanya untuk user dengan role ADMIN
        $this->middleware('role:ADMIN');
    }
}
