<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\Doctor;
use Illuminate\Support\Collection;

class AppointmentService
{
    public function getDoctorAppointments(Doctor $doctor): Collection
    {
        return Appointment::forDoctor($doctor->id)
            ->with(['patient.user'])
            ->orderBy('appointment_date')
            ->get();
    }


    public function confirmAppointment(Appointment $appointment, Doctor $doctor): ?Appointment
    {
        $appointment = Appointment::query()
            ->whereKey($appointment->id)
            ->forDoctor($doctor->id)
            ->whereNotIn('status', [
                Appointment::STATUS_COMPLETED,
                Appointment::STATUS_CANCELLED,
                Appointment::STATUS_UPCOMING,
            ])
            ->whereDate('appointment_date', '>=',  now()->toISOString())
            ->first();

        if (! $appointment) {
            return null;
        }

        $appointment->update([
            'status' => Appointment::STATUS_UPCOMING,
        ]);

        return $appointment->fresh();
    }


    public function cancelAppointment(Appointment $appointment, Doctor $doctor): ?Appointment
    {
        $appointment = Appointment::query()
            ->whereKey($appointment->id)
            ->forDoctor($doctor->id)
            ->whereNotIn('status', [
                Appointment::STATUS_COMPLETED,
                Appointment::STATUS_CANCELLED,
            ])
            ->first();

        if (! $appointment) {
            return null;
        }

        $appointment->update([
            'status' => Appointment::STATUS_CANCELLED,
        ]);

        return $appointment->fresh();
    }
}
