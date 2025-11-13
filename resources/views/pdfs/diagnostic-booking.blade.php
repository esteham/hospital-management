<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Diagnostic Booking — {{ $booking->id }}</title>
    <style>
        @page { size: A4; margin: 22mm 18mm; }
        html, body { height: 100%; }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            color: #0f172a;
            line-height: 1.55;
            font-size: 12.5px;
            margin: 0;
            background: #fff;
        }
        * { box-sizing: border-box; }
        .container { position: relative; z-index: 0; }

        .watermark {
            position: fixed;
            top: 50%; left: 50%;
            transform: translate(-50%, -50%) rotate(-12deg);
            opacity: .06;
            z-index: 0;
            width: 70%;
            text-align: center;
            filter: grayscale(100%);
            pointer-events: none;
        }
        .watermark img { max-width: 100%; height: auto; }

        .header {
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 10px; margin-bottom: 16px;
            position: relative; z-index: 2;
            width: 100%;
        }
        .brand-logo { width: 40px; height: 40px; object-fit: contain; }
        .brand-title { font-weight: 800; letter-spacing: .2px; color: #1d4ed8; }
        .brand-subtitle { color: #64748b; font-size: 10.5px; }

        .meta {
            text-align: right;
            font-size: 11px;
            color: #334155;
        }

        .qr-container {
            position: absolute;
            top: 0; right: 0;
            width: 14px; height: 14px;
            overflow: hidden;
        }

        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 9999px;
            font-size: 10.5px; font-weight: 700;
            border: 1px solid currentColor;
            letter-spacing: .3px;
        }
        .badge--confirmed { color: #16a34a; }
        .badge--pending { color: #d97706; }
        .badge--cancelled { color: #dc2626; }

        .booking {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 10px 12px;
            margin: 10px 0 18px;
            width: 100%;
        }

        .small { color: #64748b; font-size: 11px; }
        .id { font-weight: 800; color: #1d4ed8; letter-spacing:.3px; }

        .section { margin-bottom: 16px; position: relative; z-index: 1; }
        .section-title {
            font-weight: 800; font-size: 12.5px; color: #1d4ed8;
            border-bottom: 1px solid #e2e8f0; padding-bottom: 6px; margin-bottom: 10px;
            letter-spacing: .2px;
        }

        .info-table { width: 100%; border-collapse: collapse; }
        .info-table td { padding: 4px 6px; vertical-align: top; }
        .label { font-weight: 700; color: #0f172a; width: 135px; }
        .value { color: #334155; }

        .note {
            background: #fffbeb;
            border: 1px solid #fde68a;
            padding: 10px 12px; border-radius: 10px;
            font-size: 11.5px; color: #713f12;
            margin: 14px 0;
        }

        .footer {
            margin-top: 16px; padding-top: 10px;
            border-top: 1px solid #e2e8f0; text-align: center; color: #64748b; font-size: 10.5px;
            position: relative; z-index: 2;
        }

        .nowrap { white-space: nowrap; }

        table { page-break-inside: avoid; }
    </style>
</head>
<body>
@php
    $status = strtolower($booking->status ?? 'pending');
    $badgeClass = [
        'confirmed' => 'badge badge--confirmed',
        'pending'   => 'badge badge--pending',
        'cancelled' => 'badge badge--cancelled',
    ][$status] ?? 'badge badge--pending';
@endphp

<div class="watermark">
    <img src="{{ $watermarkLogoPath ?? (isset($logoPath) ? $logoPath : public_path('images/logo.png')) }}" alt="Xet Specialized Hospital" />
</div>

<div class="container">
    <!-- Header -->
    <table class="header">
        <tr>
            <td style="width:70%; vertical-align:top;">
                <table>
                    <tr>
                        <td style="width:45px;">
                            <img class="brand-logo" src="{{ $logoPath ?? public_path('images/logo.png') }}" alt="Xet Logo" />
                        </td>
                        <td>
                            <div class="brand-title">Xet Specialized Hospital</div>
                            <div class="brand-subtitle">Diagnostic Test Booking • Official Document</div>
                        </td>
                    </tr>
                </table>
            </td>
            <td style="width:30%; text-align:right; vertical-align:top;" class="meta">
                <div><strong>Date:</strong> {{ now()->format('F j, Y') }}</div>
                <div><strong>Time:</strong> {{ now()->format('g:i A') }}</div>
                <div><span class="{{ $badgeClass }}">{{ ucfirst($status) }}</span></div>
            </td>
        </tr>
    </table>

    <div class="qr-container">
        @if(!empty($qrSvg))
            {!! $qrSvg !!}
        @elseif(class_exists('SimpleSoftwareIO\\QrCode\\Facades\\QrCode'))
            {!! SimpleSoftwareIO\QrCode\Facades\QrCode::size(14)->generate('DB-' . $booking->id) !!}
        @else
            <div class="qr-code" style="border:1px dashed #cbd5e1; padding:2px; text-align:center; font-size:6px;">
                QR
            </div>
        @endif
    </div>

    <!-- Booking Info -->
    <table class="booking">
        <tr>
            <td style="width:50%;">
                <div class="small">Booking ID</div>
                <div class="id">DB-{{ $booking->id }}</div>
            </td>
            <td style="text-align:right;">
                <div class="small">Please bring a valid photo ID and arrive 15 minutes early.</div>
            </td>
        </tr>
    </table>

    <!-- Two-column layout using table -->
    <table style="width:100%; border-collapse:collapse; margin-bottom:16px;">
        <tr valign="top">
            <!-- Patient Info -->
            <td style="width:50%; padding-right:10px;">
                <div class="section">
                    <div class="section-title">Patient Information</div>
                    <table class="info-table">
                        <tr><td class="label">Full Name</td><td class="value">{{ $booking->first_name }} {{ $booking->last_name }}</td></tr>
                        <tr><td class="label">Gender</td><td class="value">{{ $booking->gender }}</td></tr>
                        <tr><td class="label">Age</td><td class="value">{{ $booking->age }}</td></tr>
                        <tr><td class="label">Email</td><td class="value">{{ $booking->email }}</td></tr>
                        <tr><td class="label">Phone</td><td class="value">{{ $booking->phone }}</td></tr>
                        <tr><td class="label">Address</td><td class="value">{{ $booking->address }}</td></tr>
                    </table>
                </div>
            </td>

            <!-- Test Info -->
            <td style="width:50%; padding-left:10px;">
                <div class="section">
                    <div class="section-title">Test Details</div>
                    <table class="info-table">
                        <tr><td class="label">Test Name</td><td class="value">{{ $booking->diagnosticService->name ?? 'N/A' }}</td></tr>
                        <tr><td class="label">Category</td><td class="value">{{ $booking->diagnosticService->category ?? 'N/A' }}</td></tr>
                        <tr><td class="label">Booking Date</td><td class="value">{{ $booking->booking_date ? $booking->booking_date->format('F j, Y') : 'N/A' }}</td></tr>
                        <tr><td class="label">Booking Time</td><td class="value">{{ $booking->booking_time ? date('g:i A', strtotime($booking->booking_time)) : 'N/A' }}</td></tr>
                        <tr><td class="label">Payment Method</td><td class="value">{{ ucfirst($booking->payment_method) }}</td></tr>
                        <tr><td class="label">Status</td><td class="value">{{ ucfirst($booking->status) }}</td></tr>
                        @if($booking->doctor)
                            <tr><td class="label">Preferred Doctor</td><td class="value">{{ $booking->doctor->name }}</td></tr>
                        @endif
                        @if(!empty($booking->additional_notes))
                            <tr><td class="label">Notes</td><td class="value">{{ $booking->additional_notes }}</td></tr>
                        @endif
                    </table>
                </div>
            </td>
        </tr>
    </table>

    <!-- Important Note -->
    <div class="note">
        <strong>Important:</strong> Please carry this confirmation (printed or digital) and a valid ID. Our collection team will visit your address at the scheduled time for sample collection.
    </div>

    <!-- Footer -->
    <div class="footer">
        <div>Document generated on {{ now()->format('F j, Y \a\t g:i A') }} <span class="nowrap">(Booking: DB-{{ $booking->id }})</span></div>
        <div>&copy; {{ date('Y') }} Xet Specialized Hospital. All rights reserved.</div>
    </div>
</div>
</body>
</html>
