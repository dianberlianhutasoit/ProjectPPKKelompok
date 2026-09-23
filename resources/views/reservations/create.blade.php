@extends('layouts.layout_pasien')

@section('title', 'Form Reservasi Dokter')

@section('content')
<div class="container py-4" style="max-width: 900px;">
    <!-- Header Page -->
    <div class="d-flex align-items-center mb-4">
        <a href="{{ route('pasien.reservasi.pilih-jadwal') }}" class="btn btn-outline-secondary btn-sm rounded-circle me-3" style="width: 32px; height: 32px; padding: 3px 0;">
            <i class="fas fa-arrow-left"></i>
        </a>
        <h4 class="fw-bold mb-0 text-dark">Formulir Reservasi Dokter</h4>
    </div>

    <div class="row g-4">
        <!-- Kolom Kiri: Card Informasi Dokter -->
        <div class="col-md-5">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body p-4 text-center">
                    <img src="{{ $dokter->user->foto_profil ? asset('storage/' . $dokter->user->foto_profil) : asset('assets/img/default-doctor.jpg') }}" 
                         alt="Foto Dokter" 
                         class="rounded-circle mb-3 object-fit-cover shadow-sm" 
                         style="width: 100px; height: 100px;">
                    <h5 class="fw-bold mb-1">{{ $dokter->user->name }}</h5>
                    <p class="text-primary fw-semibold mb-3">{{ $dokter->spesialisasi }}</p>
                    <hr class="text-muted opacity-25">
                    
                    <div class="text-start fs-7">
                        <div class="d-flex align-items-center mb-2">
                            <i class="far fa-calendar-alt text-muted me-2" style="width: 20px;"></i>
                            <span>Hari: <strong>{{ $jadwal->hari }}</strong></span>
                        </div>
                        <div class="d-flex align-items-center mb-2">
                            <i class="far fa-clock text-muted me-2" style="width: 20px;"></i>
                            <span>Jam: <strong>{{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') }}</strong></span>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="fas fa-money-bill-wave text-muted me-2" style="width: 20px;"></i>
                            <span>Biaya Konsultasi: <strong>Rp {{ number_format($dokter->biaya_konsultasi, 0, ',', '.') }}</strong></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kolom Kanan: Form Input Reservasi -->
        <div class="col-md-7">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body p-4">
                    <form action="{{ route('pasien.reservasi.store') }}" method="POST">
                        @csrf
                        
                        <!-- Hidden Inputs -->
                        <input type="hidden" name="jadwal_id" value="{{ $jadwal->id }}">
                        <input type="hidden" name="tanggal_reservasi" value="{{ $tanggal_reservasi }}">

                        <div class="mb-3">
                            <label class="form-label fw-semibold text-muted fs-7">Tanggal Konsultasi</label>
                            <input type="text" class="form-control bg-light" value="{{ \Carbon\Carbon::parse($tanggal_reservasi)->isoFormat('D MMMM Y') }}" readonly>
                        </div>

                        <div class="mb-3">
                            <label for="keluhan" class="form-label fw-semibold">Keluhan Utama <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('keluhan') is-invalid @enderror" 
                                      id="keluhan" 
                                      name="keluhan" 
                                      rows="4" 
                                      placeholder="Jelaskan secara singkat gejala atau keluhan yang Anda rasakan..." 
                                      required>{{ old('keluhan') }}</textarea>
                            @error('keluhan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="catatan" class="form-label fw-semibold">Catatan Tambahan (Opsional)</label>
                            <textarea class="form-control @error('catatan') is-invalid @enderror" 
                                      id="catatan" 
                                      name="catatan" 
                                      rows="2" 
                                      placeholder="Riwayat alergi obat, obat yang sedang dikonsumsi, dll.">{{ old('catatan') }}</textarea>
                            @error('catatan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary w-100 fw-bold py-2 shadow-sm">
                            Konfirmasi Reservasi <i class="fas fa-chevron-right ms-1"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection