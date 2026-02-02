<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAppointmentIsPaid
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        // 1. جلب رقم الموعد من الطلب
        $appointmentId = $request->input('appointment_id');
        $appointment = Appointment::find($appointmentId);

        // 2. التحقق من حالة الدفع
        if (!$appointment || $appointment->status !== 'paid') {
            return response()->json([
                'message' => 'Access Denied. Consultation must be paid before issuing a prescription.'
            ], 403);
        }

        return $next($request);
    }
}
