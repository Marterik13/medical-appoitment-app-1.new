<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
// Borra cualquier otro import de BloodType que tengas y usa este:
use App\Models\BloodType; 

class BloodTypeSeeder extends Seeder
{
    public function run(): void
    {
        $bloodTypes = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];

        foreach ($bloodTypes as $type) {
            // Usamos la ruta completa para que no haya pierde
            \App\Models\BloodType::firstOrCreate(['name' => $type]);
        }
    }
}