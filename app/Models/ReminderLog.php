<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReminderLog extends Model
{
    use HasFactory;

    protected $fillable = ['medicine_reminder_id', 'scheduled_date', 'status', 'taken_at'];

    protected $casts = [
        'scheduled_date' => 'date',
        'taken_at' => 'datetime',
    ];

    public function reminder()
    {
        return $this->belongsTo(MedicineReminder::class);
    }
}