<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReservationRequest extends FormRequest
{
    /**
     * Tentukan siapa yang boleh menggunakan request ini.
     */
    public function authorize(): bool
    {
        return $this->user()?->role === 'USER';
    }

    /**
     * Aturan validasi pengajuan reservasi.
     */
    public function rules(): array
    {
        return [
            'participants' => 'required|integer|min:1',
            'date' => 'required|date|after_or_equal:today',
            'start_time' => [
                'required',
                'date_format:H:i',
                'after_or_equal:07:00',
                'before:20:00',
            ],
            'end_time' => [
                'required',
                'date_format:H:i',
                'after:07:00',
                'before_or_equal:20:00',
            ],
            'purpose' => 'required|string',
        ];
    }

    /**
     * Pesan validasi yang lebih mudah dipahami pengguna.
     */
    public function messages(): array
    {
        return [
            'participants.required' => 'Jumlah peserta wajib diisi.',
            'participants.min' => 'Jumlah peserta minimal 1 orang.',
            'date.required' => 'Tanggal reservasi wajib diisi.',
            'date.after_or_equal' => 'Tanggal reservasi tidak boleh sebelum hari ini.',
            'start_time.required' => 'Jam mulai wajib diisi.',
            'start_time.date_format' => 'Format jam mulai harus HH:MM.',
            'start_time.after_or_equal' => 'Reservasi hanya dapat dimulai mulai pukul 07:00.',
            'start_time.before' => 'Jam mulai harus sebelum pukul 20:00.',
            'end_time.required' => 'Jam selesai wajib diisi.',
            'end_time.date_format' => 'Format jam selesai harus HH:MM.',
            'end_time.after' => 'Jam selesai harus setelah jam mulai.',
            'end_time.before_or_equal' => 'Reservasi maksimal sampai pukul 20:00.',
            'purpose.required' => 'Tujuan reservasi wajib diisi.',
        ];
    }
}