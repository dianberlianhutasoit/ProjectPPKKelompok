<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class ReservationRequest extends FormRequest
{
    /**
     * Tentukan siapa yang boleh menggunakan request ini.
     * Mengikuti standar Person 1 (Hanya role USER).
     */
    public function authorize(): bool
    {
        return $this->user()?->role === 'USER';
    }

    /**
     * Aturan validasi pengajuan reservasi.
     * Menggunakan nama field dari form Person 1 (identity_number, participants, date).
     */
    public function rules(): array
    {
        return [
            'identity_number' => 'required|string|max:50',
            'participants'    => 'required|integer|min:1',
            'date'            => 'required|date|after_or_equal:today',
            'start_time'      => [
                'required',
                'date_format:H:i',
                'after_or_equal:07:00',
                'before:20:00',
            ],
            'end_time'        => [
                'required',
                'date_format:H:i',
                'after:07:00',
                'before_or_equal:20:00',
            ],
            'purpose'         => 'required|string|max:255',
        ];
    }

    /**
     * Logika Tambahan milikmu: Validasi kelipatan 30 menit.
     */
    public function withValidator(Validator $validator): void
    {
        $validator->after(function ($validator) {
            $startTime = $this->input('start_time');
            $endTime   = $this->input('end_time');

            if ($startTime && $endTime) {
                try {
                    $start = \Carbon\Carbon::createFromFormat('H:i', $startTime);
                    $end   = \Carbon\Carbon::createFromFormat('H:i', $endTime);

                    if ($start->minute % 30 !== 0 || $end->minute % 30 !== 0) {
                        $validator->errors()->add('start_time', 'Waktu mulai dan selesai harus dalam kelipatan 30 menit (contoh: 07:00, 07:30, 08:00).');
                    }
                } catch (\Exception $e) {
                    // Abaikan jika format jam tidak valid, akan ditangani oleh rule date_format
                }
            }
        });
    }

    /**
     * Pesan validasi yang disesuaikan.
     */
    public function messages(): array
    {
        return [
            'identity_number.required'  => 'NIM/NIP wajib diisi.',
            'participants.required'     => 'Jumlah peserta wajib diisi.',
            'participants.min'          => 'Jumlah peserta minimal 1 orang.',
            'date.required'             => 'Tanggal reservasi wajib diisi.',
            'date.after_or_equal'       => 'Tanggal reservasi tidak boleh sebelum hari ini.',
            'start_time.required'       => 'Jam mulai wajib diisi.',
            'start_time.date_format'    => 'Format jam mulai harus HH:MM.',
            'start_time.after_or_equal' => 'Reservasi hanya dapat dimulai mulai pukul 07:00.',
            'start_time.before'         => 'Jam mulai harus sebelum pukul 20:00.',
            'end_time.required'         => 'Jam selesai wajib diisi.',
            'end_time.date_format'      => 'Format jam selesai harus HH:MM.',
            'end_time.after'            => 'Jam selesai harus setelah jam mulai.',
            'end_time.before_or_equal'  => 'Reservasi maksimal sampai pukul 20:00.',
            'purpose.required'          => 'Tujuan reservasi wajib diisi.',
        ];
    }
}