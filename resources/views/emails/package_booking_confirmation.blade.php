<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Package Booking Confirmation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #007bff;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }
        .content {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 0 0 5px 5px;
        }
        .booking-details {
            background-color: white;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
            border-left: 4px solid #007bff;
        }
        .booking-id {
            font-size: 18px;
            font-weight: bold;
            color: #007bff;
            margin-bottom: 10px;
        }
        .footer {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #dee2e6;
            text-align: center;
            color: #6c757d;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Package Booking Confirmed</h1>
        <p>Your health package has been successfully booked</p>
    </div>

    <div class="content">
        <p>Dear {{ $packageBooking->user->name }},</p>

        <p>Thank you for booking a health package with Xet Specialized Hospital. Your booking details are confirmed and attached to this email as a PDF receipt for your records.</p>

        <div class="booking-details">
            <div class="booking-id">Booking ID: {{ $packageBooking->id }}</div>
            <p><strong>Patient Name:</strong> {{ $packageBooking->user->name }}</p>
            <p><strong>Email:</strong> {{ $packageBooking->user->email }}</p>
            <p><strong>Package:</strong> {{ $packageBooking->healthCheck->name }}</p>
            <p><strong>Payment Type:</strong> {{ $packageBooking->payment_type }}</p>
            <p><strong>Amount Paid:</strong> {{ $packageBooking->amount_paid }}</p>
            <p><strong>Total Amount:</strong> {{ $packageBooking->total_amount }}</p>
            <p><strong>Status:</strong> {{ ucfirst($packageBooking->status) }}</p>
        </div>

        <p>Please keep this booking ID for your reference. You can use it to track your booking status or make any changes.</p>

        <p>If you have any questions or need to reschedule, please contact us at appointments@xethospital.com or call us at 999.</p>

        <p>We look forward to serving you!</p>

        <p>Best regards,<br>
        Xet Specialized Hospital Team</p>
    </div>

    <div class="footer">
        <p>This is an automated email. Please do not reply to this message.</p>
        <p>&copy; {{ date('Y') }} Xet Specialized Hospital. All rights reserved.</p>
    </div>
</body>
</html>
