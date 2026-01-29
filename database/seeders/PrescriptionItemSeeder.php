<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Prescription;
use App\Models\PrescriptionItem;

class PrescriptionItemSeeder extends Seeder
{
    public function run(): void
    {
        $prescriptions = Prescription::all();

        foreach ($prescriptions as $prescription) {
            $count = rand(1, 3);

            for ($i = 0; $i < $count; $i++) {
                PrescriptionItem::create([
                    'prescription_id' => $prescription->id,
                    'medicine_name'   => 'Medicine ' . ($i + 1),
                    'dosage'         => rand(100, 500) . 'mg',
                    'frequency'      => 'Once or twice daily',
                    'duration_days'  => rand(3, 10),
                ]);
            }
        }
    }
}
