<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\Doctor;
use Illuminate\Support\Collection;

class AppointmentService
{
    public function getDoctorAppointments(Doctor $doctor): Collection
    {
        return Appointment::where('doctor_id', $doctor->id)
            ->with(['patient.user'])
            ->orderBy('appointment_date')
            ->get();
    }


    public function confirmAppointment(Appointment $appointment, Doctor $doctor): ?Appointment
    {
        if ($appointment->doctor_id !== $doctor->id) {
            return null;
        }

        if (in_array($appointment->status, [
            Appointment::STATUS_COMPLETED,
            Appointment::STATUS_CANCELLED,
            Appointment::STATUS_UPCOMING,
        ])) {
            return null;
        }

        if ($appointment->appointment_date < now()->toDateString()) {
            return null;
        }

        $appointment->update([
            'status' => Appointment::STATUS_UPCOMING,
        ]);

        return $appointment->fresh();
    }

    public function cancelAppointment(Appointment $appointment, Doctor $doctor): ?Appointment
    {
        if ($appointment->doctor_id !== $doctor->id) {
            return null;
        }

        if (in_array($appointment->status, [
            Appointment::STATUS_COMPLETED,
            Appointment::STATUS_CANCELLED,
        ])) {
            return null;
        }

        $appointment->update([
            'status' => Appointment::STATUS_CANCELLED,
        ]);

        return $appointment->fresh();
    }
}
