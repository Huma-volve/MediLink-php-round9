<!DOCTYPE html>
<html dir="rtl">

<head>


    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            direction: rtl;
            text-align: right;
        }

        .doctor-info {
            float: left;
            width: 45%;
        }

        .patient-info {
            float: right;
            width: 45%;
        }

        .header {
            border-bottom: 2px solid #2D6A4F;
            padding-bottom: 10px;
            margin-bottom: 20px;
            text-align: center;
        }

        .doctor-info {
            float: right;
            width: 50%;
        }

        .patient-info {
            float: left;
            width: 50%;
            text-align: left;
        }

        .clear {
            clear: both;
        }

        .content {
            margin-top: 30px;
            border: 1px solid #eee;
            padding: 20px;
            min-height: 300px;
        }

        .footer {
            margin-top: 50px;
            font-size: 12px;
            text-align: center;
            color: #777;
        }

        .rx {
            font-size: 24px;
            color: #2D6A4F;
            font-weight: bold;
        }

        body {
            font-family: 'sans-serif';
            direction: rtl;
            text-align: right;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1 style="color: #2D6A4F;">MediLink Medical Center</h1>
        <p>Your Health, Our Priority</p>
    </div>




    <div class="doctor-info">
        <strong>Doctor:</strong>
        {{ $appointment->doctor->user->name ?? 'Not Assigned' }} <br>

        <strong>Specialization:</strong>
        {{ $appointment->doctor->specialization->name ?? 'General' }}

    </div>


    <div class="patient-info">
        <strong>Patient:</strong> {{ $appointment->patient->user->name ?? 'اسم المريض غير موجود' }} <br>
    </div>

    {{-- {{ gettype($prescription->medications) }} --}}

    <div class="clear"></div>

    <div class="content">
        <span class="rx">R/</span>
        <p style="margin-top: 20px;">

        <p><strong>Diagnosis:</strong> {{ $prescription->diagnosis }}</p>

        <p><strong>Medications:</strong></p>
        <ul>

            @if (is_array($prescription->medications))
                <ul>
                    @foreach ($prescription->medications as $med)
                        <li>{{ $med }}</li>
                    @endforeach
                </ul>
            @else
                <p>{{ $prescription->medications }}</p>
            @endif



        </ul>

        <p><strong>Frequency:</strong> {{ $prescription->frequency }}</p>
        <p><strong>Duration:</strong> {{ $prescription->duration_days }} days</p>



        </p>
    </div>

    <div class="footer">
        This is an electronically generated prescription. No signature required. <br>
        Address: 123 Health St, Medical City
    </div>
</body>

</html>
