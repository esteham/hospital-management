<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment Details - {{ $appointment->booking_id }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #007bff;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #007bff;
            margin-bottom: 10px;
        }
        .title {
            font-size: 20px;
            color: #333;
            margin-bottom: 5px;
        }
        .subtitle {
            color: #666;
            font-size: 14px;
        }
        .booking-id {
            background-color: #f8f9fa;
            padding: 10px;
            border-radius: 5px;
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            color: #007bff;
            margin-bottom: 20px;
        }
        .details-section {
            margin-bottom: 30px;
        }
        .section-title {
            font-size: 16px;
            font-weight: bold;
            color: #007bff;
            border-bottom: 1px solid #dee2e6;
            padding-bottom: 5px;
            margin-bottom: 15px;
        }
        .detail-row {
            display: flex;
            margin-bottom: 8px;
        }
        .detail-label {
            font-weight: bold;
            width: 150px;
            flex-shrink: 0;
        }
        .detail-value {
            flex: 1;
        }
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #dee2e6;
            text-align: center;
            color: #666;
            font-size: 12px;
        }
        .important-note {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .qr-code {
            text-align: center;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">Xet Specialized Hospital</div>
        <div class="title">Appointment Confirmation</div>
        <div class="subtitle">Official Appointment Details</div>
    </div>

    <div class="booking-id">
        Booking ID: {{ $appointment->booking_id }}
    </div>

    <div class="details-section">
        <div class="section-title">Patient Information</div>
        <div class="detail-row">
            <div class="detail-label">Full Name:</div>
            <div class="detail-value">{{ $appointment->first_name }} {{ $appointment->last_name }}</div>
        </div>
        <div class="detail-row">
            <div class="detail-label">Email:</div>
            <div class="detail-value">{{ $appointment->email }}</div>
        </div>
        <div class="detail-row">
            <div class="detail-label">Phone:</div>
            <div class="detail-value">{{ $appointment->phone }}</div>
        </div>
    </div>

    <div class="details-section">
        <div class="section-title">Appointment Details</div>
        <div class="detail-row">
            <div class="detail-label">Preferred Date:</div>
            <div class="detail-value">{{ \Carbon\Carbon::parse($appointment->preferred_date)->format('F j, Y') }}</div>
        </div>
        <div class="detail-row">
            <div class="detail-label">Preferred Time:</div>
            <div class="detail-value">{{ $appointment->preferred_time }}</div>
        </div>
        <div class="detail-row">
            <div class="detail-label">Speciality:</div>
            <div class="detail-value">{{ $appointment->speciality }}</div>
        </div>
        <div class="detail-row">
            <div class="detail-label">Status:</div>
            <div class="detail-value">{{ ucfirst($appointment->status) }}</div>
        </div>
        @if($appointment->additional_notes)
        <div class="detail-row">
            <div class="detail-label">Additional Notes:</div>
            <div class="detail-value">{{ $appointment->additional_notes }}</div>
        </div>
        @endif
    </div>

    <div class="important-note">
        <strong>Important:</strong> Please bring this document and a valid ID to your appointment. Arrive 15 minutes early for registration.
    </div>

    <div class="qr-code">
        <!-- You can add a QR code here that encodes the booking ID -->
        <div style="border: 1px solid #ccc; padding: 20px; display: inline-block;">
            QR Code Placeholder<br>
            (Booking ID: {{ $appointment->booking_id }})
        </div>
    </div>

    <div class="footer">
        <p>This document was generated on {{ now()->format('F j, Y \a\t g:i A') }}</p>
        <p>For any changes or questions, contact us at appointments@xethospital.com or call 999</p>
        <p>&copy; {{ date('Y') }} Xet Specialized Hospital. All rights reserved.</p>
    </div>
</body>
</html>
