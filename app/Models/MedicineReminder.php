<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicineReminder extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'medicine_name', 'dosage_form', 'dosage', 'note',
        'time', 'start_date', 'repeat_days', 'frequency', 'is_active',
    ];

    protected $casts = [
        'repeat_days' => 'array',
        'is_active' => 'boolean',
        'start_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function logs()
    {
        return $this->hasMany(ReminderLog::class);
    }

    // Cek status "diminum" hari ini
    public function getTodayStatusAttribute()
    {
        $log = $this->logs()->whereDate('scheduled_date', today())->first();
        return $log->status ?? 'belum';
    }
}