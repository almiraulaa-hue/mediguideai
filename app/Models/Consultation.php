<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Consultation extends Model
{
    use HasFactory;

    protected $fillable = [
    'user_id', 'title', 'main_complaint', 'duration', 'severity',
    'additional_symptoms', 'has_taken_medicine', 'ai_recommendation', 'risk_flag', 'status',
];

    protected $casts = [
        'ai_recommendation' => 'array',
        'risk_flag' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function messages()
    {
        return $this->hasMany(ConsultationMessage::class);
    }
}