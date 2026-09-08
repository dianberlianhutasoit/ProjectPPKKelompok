<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator; // <-- Tambahkan import ini

class ReservationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'facility_id'      => 'required|exists:facilities,id',
            'reservation_date' => 'required|date|after_or_equal:today',
            'start_time'       => 'required|date_format:H:i',
            'end_time'         => 'required|date_format:H:i|after:start_time',
            'purpose'          => 'required|string|max:255',
        ];
    }

    public function withValidator(Validator $validator): void // <-- Tambahkan type hint Validator di sini
    {
        $validator->after(function ($validator) {
            $startTime = $this->input('start_time');
            $endTime   = $this->input('end_time');

            if ($startTime && $endTime) {
                $start = \Carbon\Carbon::createFromFormat('H:i', $startTime);
                $end   = \Carbon\Carbon::createFromFormat('H:i', $endTime);

                $operationalStart = \Carbon\Carbon::createFromFormat('H:i', '07:00');
                $operationalEnd   = \Carbon\Carbon::createFromFormat('H:i', '20:00');

                if ($start->lt($operationalStart) || $end->gt($operationalEnd)) {
                    $validator->errors()->add('start_time', 'Reservasi hanya diizinkan pada jam operasional (07:00 - 20:00).');
                }

                if ($start->minute % 30 !== 0 || $end->minute % 30 !== 0) {
                    $validator->errors()->add('start_time', 'Waktu mulai dan selesai harus dalam kelipatan 30 menit (contoh: 07:00, 07:30, 08:00).');
                }
            }
        });
    }

    public function messages(): array
    {
        return [
            'facility_id.required'      => 'Fasilitas wajib dipilih.',
            'reservation_date.required' => 'Tanggal reservasi wajib diisi.',
            'reservation_date.after_or_equal' => 'Tanggal reservasi tidak boleh tanggal yang sudah lewat.',
            'start_time.required'       => 'Jam mulai wajib diisi.',
            'end_time.required'         => 'Jam selesai wajib diisi.',
            'end_time.after'            => 'Jam selesai harus setelah jam mulai.',
            'purpose.required'          => 'Tujuan penggunaan fasilitas wajib diisi.',
        ];
    }
}