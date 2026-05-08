<?php

namespace Database\Seeders;

use App\Models\Doctor;
use App\Models\DoctorSchedule;
use App\Models\Patient;
use App\Models\User;
use App\Models\Appointment;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class DemoDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 0. Seed blood types if needed
        $this->call(BloodTypeSeeder::class);

        // 1. Crear Doctores
        $doctors = [
            ['name' => 'Dr. Luis Torres', 'email' => 'luis@demo.com', 'dni' => '10000001', 'phone' => '555-0001', 'specialty' => 'Endocrinología'],
            ['name' => 'Dra. Ana Gómez', 'email' => 'ana@demo.com', 'dni' => '10000002', 'phone' => '555-0002', 'specialty' => 'Dermatología'],
            ['name' => 'Dr. Carlos Pérez', 'email' => 'carlos@demo.com', 'dni' => '10000003', 'phone' => '555-0003', 'specialty' => 'Cardiología'],
            ['name' => 'Dra. Ariadna Saavedra', 'email' => 'ariadna@demo.com', 'dni' => '10000004', 'phone' => '555-0004', 'specialty' => 'Gastroenterología'],
        ];

        $createdDoctors = [];
        foreach ($doctors as $docData) {
            $doctor = Doctor::firstOrCreate(['email' => $docData['email']], $docData);
            $createdDoctors[] = $doctor;
            
            // Crear horario para el doctor (Lunes a Viernes de 09:00 a 13:00)
            if (DoctorSchedule::where('doctor_id', $doctor->id)->count() === 0) {
                for ($day = 1; $day <= 5; $day++) { // 1=Lunes, 5=Viernes
                    for ($hour = 9; $hour < 13; $hour++) {
                        $minutes = ['00', '15', '30', '45'];
                        foreach ($minutes as $min) {
                            $start = Carbon::createFromTime($hour, (int)$min);
                            $end = $start->copy()->addMinutes(15);
                            
                            DoctorSchedule::create([
                                'doctor_id' => $doctor->id,
                                'day_of_week' => $day,
                                'start_time' => $start->format('H:i:s'),
                                'end_time' => $end->format('H:i:s'),
                            ]);
                        }
                    }
                }
            }
        }

        // 2. Crear Pacientes
        $createdPatients = [];
        $bloodTypes = \App\Models\BloodType::pluck('id')->toArray();
        if (empty($bloodTypes)) {
            $bloodTypes = [1]; // fallback
        }

        $patientData = [
            [
                'name' => 'Óscar García', 'email' => 'oscar@demo.com', 'id_number' => '70000001',
                'allergies' => 'Penicilina, Polen', 'chronic_conditions' => 'Hipertensión arterial',
                'surgical_history' => 'Apendicectomía (2015)'
            ],
            [
                'name' => 'Isabel Ruiz', 'email' => 'isabel@demo.com', 'id_number' => '70000002',
                'allergies' => 'Ninguna registrada', 'chronic_conditions' => 'Asma bronquial',
                'surgical_history' => 'Ninguno'
            ],
            [
                'name' => 'Danilito', 'email' => 'danilito@demo.com', 'id_number' => '55551',
                'allergies' => 'No registradas', 'chronic_conditions' => 'No registradas',
                'surgical_history' => 'No registrados'
            ],
        ];

        foreach ($patientData as $index => $pData) {
            $user = User::firstOrCreate(['email' => $pData['email']], [
                'name' => $pData['name'],
                'password' => bcrypt('password'),
                'id_number' => $pData['id_number'],
                'phone' => "555-100{$index}",
                'address' => "Calle Demo {$index}",
            ]);

            $patient = Patient::firstOrCreate(['user_id' => $user->id], [
                'blood_type_id' => $bloodTypes[array_rand($bloodTypes)],
                'allergies' => $pData['allergies'],
                'chronic_conditions' => $pData['chronic_conditions'],
                'surgical_history' => $pData['surgical_history']
            ]);
            $createdPatients[] = $patient;
        }

        // 3. Crear Citas (Appointments) - Consultas anteriores
        if (Appointment::count() === 0) {
            $pastDates = [
                Carbon::now()->subDays(10)->format('Y-m-d'),
                Carbon::now()->subDays(25)->format('Y-m-d'),
                Carbon::now()->subDays(45)->format('Y-m-d'),
            ];

            $diagnoses = [
                'Gastritis aguda', 'Infección respiratoria aguda', 'Migraña crónica', 'Faringitis estreptocócica'
            ];
            
            $treatments = [
                'Omeprazol 20mg cada 24h por 14 días. Dieta blanda.',
                'Amoxicilina 500mg cada 8 horas por 7 días. Reposo absoluto.',
                'Ibuprofeno 400mg en caso de dolor. Evitar estrés.',
                'Azitromicina 500mg por 3 días. Abundantes líquidos.'
            ];

            foreach ($createdPatients as $patient) {
                foreach ($pastDates as $date) {
                    $doctor = $createdDoctors[array_rand($createdDoctors)];
                    
                    Appointment::create([
                        'patient_id' => $patient->id,
                        'doctor_id' => $doctor->id,
                        'date' => $date,
                        'start_time' => '17:30:00',
                        'end_time' => '17:45:00',
                        'status' => 2, // 2 = Completado
                        'diagnosis' => $diagnoses[array_rand($diagnoses)],
                        'treatment' => $treatments[array_rand($treatments)],
                        'notes' => 'El paciente reporta mejoría. Se recomienda revisión en 1 mes.',
                    ]);
                }
            }

            // Crear algunas citas programadas para el futuro
            foreach ($createdPatients as $patient) {
                $doctor = $createdDoctors[array_rand($createdDoctors)];
                Appointment::create([
                    'patient_id' => $patient->id,
                    'doctor_id' => $doctor->id,
                    'date' => Carbon::now()->addDays(2)->format('Y-m-d'),
                    'start_time' => '09:00:00',
                    'end_time' => '09:15:00',
                    'status' => 1, // 1 = Programado
                ]);
            }
        }
    }
}
