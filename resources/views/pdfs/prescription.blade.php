<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prescription - {{ $appointment->booking_id }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            margin: 20px 40px;
            font-size: 13px;
            color: #222;
            line-height: 1.5;
            position: relative;
        }

        /* Header using table layout for PDF stability */
        .header-table {
            width: 100%;
            border-bottom: 2px solid #000;
            margin-bottom: 14px;
            border-collapse: collapse;
        }

        .header-table td {
            vertical-align: top;
            padding: 0;
        }

        .doctor-info {
            text-align: left;
            width: 55%;
        }

        .doctor-info h2 {
            margin: 0;
            font-size: 18px;
            font-weight: 700;
            color: #111;
        }

        .doctor-info p {
            margin: 2px 0;
            font-size: 10px;
        }

        .hospital-info {
            text-align: right;
            width: 45%;
        }

        .hospital-info h3 {
            margin: 0;
            font-size: 16px;
            font-weight: 700;
            color: #3686ffff;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .hospital-info p {
            margin: 3px 0;
            color: #333;
            font-size: 10px;
        }

        /* Patient & medicine font */
        .patients{
            font-family: "Tinos";
        }

        .medicine{
            font-family: "Tinos";
        }

        /* Patient & diagnosis section */
        .section {
            margin-top: 15px;
            margin-bottom: 10px;
        }

        .section p {
            margin: 3px 0;
        }

        /* Prescription table */
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 12px;
        }

        .table th, .table td {
            border: 1px solid #aaa;
            padding: 8px;
            text-align: left;
        }

        .table th {
            background: #f5f5f5;
            font-weight: 600;
        }

        .table td {
            vertical-align: top;
        }

        /* Advice and footer */
        .advice {
            font-weight: 600;
            margin-top: 15px;
            border-top: 1px dashed #aaa;
            padding-top: 8px;
        }

        .footer {
            margin-top: 30px;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
        }

        .footer p {
            margin: 0;
        }

        /* Watermark */
        .watermark {
            position: absolute;
            top: 0;
            left: 33%;
            opacity: 0.12;
            transform: rotate(-25deg);
            pointer-events: none;
            user-select: none;
        }
        .img{
            width: 160px;
            height: auto;
        }
    </style>
</head>
<body>

    <!-- Header -->
    <table class="header-table">
        <tr>
            <td class="doctor-info">
                <h2>{{ $appointment->doctor->user->name }}</h2>
                <p>{{ $appointment->doctor->designation }}</p>
                <p>{{ $appointment->doctor->about }}</p>
                <p>Reg. No: {{ $appointment->doctor->registration_no ?? 'N/A' }}</p>
                <p>Mob: {{ $appointment->doctor->user->phone ?? '—' }}</p>
            </td>
            <td class="hospital-info">
                <h3>Xet Specialized Hospital</h3>
                <p>{{ $appointment->doctor->hospital_address ?? '123 Medical Avenue - 12345' }}</p>
                <p>Phone: {{ $appointment->doctor->hospital_phone ?? '09423380390' }}</p>
                <p>Timing: 09:00 AM – 02:00 PM</p>
                <p>Closed: Friday</p>
            </td>
        </tr>
    </table>

    <!-- Patient info and Date using table -->
    <table class="patients" style="width: 100%; margin-top: 15px; border-bottom: 1px solid #000; margin-bottom: 10px;">
        <tr>
            <td style="width: 70%; vertical-align: top;">
                <p style="margin:0;"><strong>ID:</strong> {{ $appointment->booking_id }}</p>
                <p style="margin:0;"><strong>Patient:</strong> {{ $appointment->first_name }} {{ $appointment->last_name }} ({{ $appointment->gender ?? 'M/F' }} / {{ $appointment->age ?? '—' }} Y)</p>
                <p style="font-size:10px; margin:0;"><strong>Phone:</strong> {{ $appointment->phone }}</p>
            </td>
            <td style="width: 30%; text-align: right; vertical-align: top;">
                <p style="margin:0;"><strong>Date:</strong> {{ \Carbon\Carbon::parse($appointment->preferred_date)->format('d-M-Y') }}</p>
            </td>
        </tr>
    </table>


   <!-- Two-column layout for Diagnosis+Advice and Medicines -->
    <table style="width:100%; border-collapse: collapse; margin-top: 15px;">
        <tr>
            <!-- Left column: Diagnosis + Advice -->
            <td style="width:30.33%; vertical-align: top; padding-right:10px; border:none;">
                <!-- Diagnosis -->
                @php
                    preg_match('/Diagnosis:\s*(.*?)(\d+\)|Advice:|$)/s', $prescription->prescription_text, $matches);
                    $diagnosis = trim($matches[1] ?? '—');
                @endphp
                <p style="margin:0 0 10px 0;"><strong>Diagnosis:</strong><br/> {{ $diagnosis }}</p>
                <br/>

                <!-- Advice -->
                @php
                    $advice = '';
                    if (str_contains($prescription->prescription_text, 'Advice:')) {
                        $advice = trim(Str::after($prescription->prescription_text, 'Advice:'));
                    }
                @endphp
                @if(!empty($advice))
                    <p style="margin:0;"><strong>Advice Given:</strong><br/> {{ $advice }}</p>
                @endif
            </td>

            <!-- Middle vertical line -->
            <td style="width:1%; border-left:1px solid #aaa;"></td>

            <!-- Right column: Medicines -->
            <td style="width:69.66%; vertical-align: top; padding-left:10px; border:none;">
                <table style="width:100%; border-collapse: collapse;">
                    <thead>
                        <tr>
                            <th style="text-align:left;">Medicine Name - Dosage - Duration</th>
                        </tr>
                    </thead>
                    <tbody class="medicine">
                        @php
                            $lines = preg_split("/\r\n|\n|\r/", trim($prescription->prescription_text));
                            $count = 1;
                        @endphp
                        @foreach($lines as $line)
                            @if(preg_match('/^\d+\)/', $line))
                                <tr>
                                    <td colspan="3" style="padding:5px 0;">{{ $line }}</td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </td>
        </tr>
    </table>


    <!-- Footer -->
    <div class="footer">
        <p><strong>Follow Up:</strong> {{ $prescription->follow_up_date ?? '—' }}</p>
        <div style="position: absolute; bottom: 40px; right: 0; text-align: right;">
    <p>______________________________</p>
    <p>{{ $appointment->doctor->user->name }}</p>
    <p>{{ $appointment->doctor->designation }}</p>
</div>
    </div>

    <!-- Watermark -->
    <div class="watermark">
        <img class="img" src="{{ public_path('images/logo.png') }}" alt="Hospital Logo">
    </div>

</body>
</html>
