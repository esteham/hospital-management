<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Package Booking Cancellation</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background-color: #f8f9fa; padding: 20px; text-align: center; }
        .content { padding: 20px; }
        .footer { background-color: #f8f9fa; padding: 20px; text-align: center; font-size: 12px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Package Booking Cancellation</h1>
        </div>
        <div class="content">
            <p>Dear {{ $user->name }},</p>
            <p>We regret to inform you that your booking for the <strong>{{ $package->name }}</strong> package has been cancelled by the administrator.</p>
            <p><strong>Cancellation Reason:</strong></p>
            <p>{{ $cancellationReason }}</p>
            <p>If you have any questions or need further assistance, please contact our support team.</p>
            <p>Thank you for your understanding.</p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} Hospital Management System. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
