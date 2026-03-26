<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 
        'allergies',
        'chronic_conditions',
        'surgical_history',
        'family_history',
        'observations',
        'emergency_contact_name',
        'emergency_contact_phone',
        'emergency_contact_relationship',
        'blood_type_id' 
    ];

    // Relación uno a uno inversa: Un paciente pertenece a un usuario
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relación uno a muchos inversa: Un paciente tiene un tipo de sangre
    public function bloodType()
    {
        return $this->belongsTo(BloodType::class);
    }
}