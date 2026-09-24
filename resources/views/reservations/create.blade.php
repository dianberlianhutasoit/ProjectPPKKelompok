@extends('layouts.app')

@section('content')
<div class="container py-4" style="max-width: 900px;">
    <!-- Header Page -->
    <div class="d-flex align-items-center mb-4">
        <a href="{{ route('facilities.show', $facility->id) }}" class="btn btn-outline-secondary btn-sm rounded-circle me-3" style="width: 32px; height: 32px; padding: 3px 0;">
            <i class="fas fa-arrow-left"></i>
        </a>
        <h4 class="fw-bold mb-0 text-dark">Formulir Reservasi Fasilitas</h4>
    </div>

    <div class="row g-4">
        <!-- Kolom Kiri: Card Informasi Fasilitas -->
        <div class="col-md-5">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body p-4 text-center">
                    <img src="{{ $facility->image ? asset('storage/' . $facility->image) : asset('assets/img/default-facility.jpg') }}" 
                         alt="{{ $facility->name }}" 
                         class="rounded-3 mb-3 object-fit-cover shadow-sm" 
                         style="width: 100%; height: 180px;">
                    
                    <h5 class="fw-bold mb-1">{{ $facility->name }}</h5>
                    <p class="text-primary fw-semibold mb-3">{{ $facility->location }}</p>
                    <hr class="text-muted opacity-25">
                    
                    <div class="text-start fs-7">
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-users text-muted me-2" style="width: 20px;"></i>
                            <span>Kapasitas: <strong>{{ $facility->capacity }} Orang</strong></span>
                        </div>
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-info-circle text-muted me-2" style="width: 20px;"></i>
                            <span>Status: <strong class="text-success">{{ $facility->status }}</strong></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kolom Kanan: Form Input Reservasi -->
        <div class="col-md-7">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body p-4">
                    <form action="{{ route('reservations.store', $facility->id) }}" method="POST">
                        @csrf

                        <!-- NIM / NIP / No Identitas -->
                        <div class="mb-3">
                            <label for="identity_number" class="form-label fw-semibold text-muted fs-7">NIM / NIP / No. Identitas</label>
                            <input type="text" 
                                   name="identity_number" 
                                   id="identity_number" 
                                   class="form-control @error('identity_number') is-invalid @enderror" 
                                   value="{{ old('identity_number') }}" 
                                   placeholder="Masukkan NIM/NIP pemohon" 
                                   required>
                            @error('identity_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Tanggal Reservasi -->
                        <div class="mb-3">
                            <label for="date" class="form-label fw-semibold text-muted fs-7">Tanggal Reservasi</label>
                            <input type="date" 
                                   name="date" 
                                   id="date" 
                                   class="form-control @error('date') is-invalid @enderror" 
                                   value="{{ old('date', date('Y-m-d')) }}" 
                                   min="{{ date('Y-m-d') }}" 
                                   required>
                            @error('date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Jam Mulai & Jam Selesai -->
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label for="start_time" class="form-label fw-semibold text-muted fs-7">Jam Mulai</label>
                                <input type="time" 
                                       name="start_time" 
                                       id="start_time" 
                                       step="1800" 
                                       class="form-control @error('start_time') is-invalid @enderror" 
                                       value="{{ old('start_time', '08:00') }}" 
                                       required>
                                @error('start_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="end_time" class="form-label fw-semibold text-muted fs-7">Jam Selesai</label>
                                <input type="time" 
                                       name="end_time" 
                                       id="end_time" 
                                       step="1800" 
                                       class="form-control @error('end_time') is-invalid @enderror" 
                                       value="{{ old('end_time', '10:00') }}" 
                                       required>
                                @error('end_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Jumlah Peserta -->
                        <div class="mb-3">
                            <label for="participants" class="form-label fw-semibold text-muted fs-7">Jumlah Peserta</label>
                            <input type="number" 
                                   name="participants" 
                                   id="participants" 
                                   class="form-control @error('participants') is-invalid @enderror" 
                                   value="{{ old('participants', 1) }}" 
                                   max="{{ $facility->capacity }}" 
                                   min="1" 
                                   required>
                            <small class="text-muted fs-8">Maksimal {{ $facility->capacity }} orang</small>
                            @error('participants')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Keperluan / Tujuan -->
                        <div class="mb-4">
                            <label for="purpose" class="form-label fw-semibold text-muted fs-7">Keperluan / Tujuan Kegiatan</label>
                            <textarea name="purpose" 
                                      id="purpose" 
                                      rows="3" 
                                      class="form-control @error('purpose') is-invalid @enderror" 
                                      placeholder="Jelaskan tujuan peminjaman fasilitas..." 
                                      required>{{ old('purpose') }}</textarea>
                            @error('purpose')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Tombol Submit -->
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary fw-semibold py-2">
                                Ajukan Reservasi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div> <!-- End Row -->
</div>
@endsection