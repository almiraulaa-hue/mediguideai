<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HealthProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'birth_date',
        'gender',
        'blood_type',
        'height',
        'allergies',
        'chronic_diseases',
        'routine_medicines',
        'other_conditions',
        'is_verified',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'allergies' => 'array',
        'chronic_diseases' => 'array',
        'routine_medicines' => 'array',
        'is_verified' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Hitung usia otomatis dari tanggal lahir (sesuai desain: "16 thn")
    public function getAgeAttribute()
    {
        return $this->birth_date ? $this->birth_date->age : null;
    }
}