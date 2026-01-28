<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Prescription;
use App\Models\Appointment;
use Carbon\Carbon;

class PrescriptionSeeder extends Seeder
{
    public function run(): void
    {
        $appointments = Appointment::inRandomOrder()->take(5)->get();

        foreach ($appointments as $appointment) {
            Prescription::create([
                'appointment_id'      => $appointment->id,
                'prescription_number' => 'RX-' . strtoupper(uniqid()),
                'medications'         => 'Panadol 500mg (2 tabs), Amoxicillin 500mg (1 cap)',
                'frequency'           => 'Every 8 hours',
                'duration_days'       => 7,
                'additional_notes'    => 'Take medication after meals. Drink plenty of water.',
                'diagnosis'           => 'Acute Pharyngitis (Infection of the throat)',
                'patient_conditions'  => 'Stable, but has mild fever.',
                'prescription_date'   => Carbon::now()->format('Y-m-d'),
                'expiry_date'         => Carbon::now()->addMonths(3)->format('Y-m-d'),
            ]);

            $appointment->update(['status' => 'completed']);
        }
    }
}
