<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'facility_id',
        'identity_number',
        'participants',
        'start_time',
        'end_time',
        'purpose',
        'status',
        'cancel_reason',
    ];

    protected function casts(): array
    {
        return [
            'start_time' => 'datetime',
            'end_time'   => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function facility()
    {
        return $this->belongsTo(Facility::class);
    }

    public function scopeFilterAndSort($query, array $filters)
    {
        if (!empty($filters['status'])) {
            $query->where('reservations.status', $filters['status']);
        } else {
            $query->where('reservations.status', 'PENDING'); 
        }

        if (!empty($filters['facility_id'])) {
            $query->where('reservations.facility_id', $filters['facility_id']);
        }

        $sortBy = $filters['sort_by'] ?? 'created_at'; 
        $sortOrder = $filters['sort_order'] ?? 'asc';  

        switch ($sortBy) {
            case 'event_date':
                $query->orderBy('reservations.start_time', $sortOrder);
                break;
            case 'facility':
                $query->join('facilities', 'reservations.facility_id', '=', 'facilities.id')
                    ->orderBy('facilities.name', $sortOrder)
                    ->select('reservations.*');
                break;
            case 'created_at':
            default:
                $query->orderBy('reservations.created_at', $sortOrder);
                break;
        }

        return $query;
    }
}