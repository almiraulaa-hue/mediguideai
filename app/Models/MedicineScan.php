<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicineScan extends Model
{
    use HasFactory;

    protected $fillable = [
    'user_id', 'medicine_name', 'category', 'dosage_form', 'packaging',
    'manufacturer', 'price_estimate', 'composition', 'indication',
    'side_effects', 'contraindication', 'image_path',
];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}