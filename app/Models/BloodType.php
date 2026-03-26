<?php

namespace App\Models; // <-- Revisa que diga 'Models' (plural)

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BloodType extends Model // <-- Revisa que diga 'BloodType'
{
    use HasFactory;
    
    protected $table = 'blood_types'; // Esto asegura que busque la tabla correcta
    protected $fillable = ['name'];
}