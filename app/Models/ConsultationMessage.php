<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConsultationMessage extends Model
{
    use HasFactory;

    protected $fillable = ['consultation_id', 'sender', 'message', 'quick_replies'];

    protected $casts = [
        'quick_replies' => 'array',
    ];

    public function consultation()
    {
        return $this->belongsTo(Consultation::class);
    }
}