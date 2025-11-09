<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Package Booking Receipt — {{ $packageBooking->id }}</title>
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

        .brand-logo { width: 40px; height: 40px; object-fit: contain; }
        .brand-title { font-weight: 800; letter-spacing: .2px; color: #1d4ed8; }
        .brand-subtitle { color: #64748b; font-size: 10.5px; }

        .meta { text-align: right; font-size: 11px; color: #334155; }

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
            border-top: 1px solid #e2e8f0;
            text-align: center; color: #64748b; font-size: 10.5px;
            position: relative; z-index: 2;
        }

        .nowrap { white-space: nowrap; }
        table { page-break-inside: avoid; }
    </style>
</head>
<body>
@php
    $status = strtolower($packageBooking->status ?? 'pending');
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
    <table style="width:100%; border-bottom:1px solid #e2e8f0; padding-bottom:10px; margin-bottom:16px;" cellspacing="0" cellpadding="0">
        <tr>
            <td style="width:70%; vertical-align:top;">
                <table>
                    <tr>
                        <td style="width:45px;">
                            <img class="brand-logo" src="{{ $logoPath ?? public_path('images/logo.png') }}" alt="Xet Logo" />
                        </td>
                        <td>
                            <div class="brand-title">Xet Specialized Hospital</div>
                            <div class="brand-subtitle">Package Booking Receipt • Official Document</div>
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

    <!-- QR Code -->
    <div style="position:absolute; top:10px; right:10px;">
        @if(!empty($qrSvg))
            {!! $qrSvg !!}
        @elseif(class_exists('SimpleSoftwareIO\\QrCode\\Facades\\QrCode'))
            {!! SimpleSoftwareIO\QrCode\Facades\QrCode::size(60)->generate($packageBooking->id) !!}
        @else
            <div style="border:1px dashed #cbd5e1; padding:4px; text-align:center; font-size:8px; width:60px; height:60px;">QR</div>
        @endif
    </div>

    <!-- Booking Strip -->
    <table class="booking" cellspacing="0" cellpadding="4">
        <tr>
            <td style="width:50%;">
                <div class="small">Booking ID</div>
                <div class="id">{{ $packageBooking->id }}</div>
            </td>
            <td style="text-align:right;">
                <div class="small">Please bring a valid photo ID and arrive 15 minutes early.</div>
            </td>
        </tr>
    </table>

    <!-- Two Column Info -->
    <table style="width:100%; border-collapse:collapse; margin-bottom:16px;">
        <tr valign="top">
            <!-- Patient Info -->
            <td style="width:50%; padding-right:10px;">
                <div class="section">
                    <div class="section-title">Patient Information</div>
                    <table class="info-table">
                        <tr><td class="label">Full Name</td><td class="value">{{ $packageBooking->user->name }}</td></tr>
                        <tr><td class="label">Email</td><td class="value">{{ $packageBooking->user->email }}</td></tr>
                        <br/>
                        <tr class="section-title" />
                        <tr><td class="label">Features</td><td class="value">{{ implode(', ', $packageBooking->healthCheck->features ?? []) }}</td></tr>
                    </table>
                </div>
            </td>

            <!-- Booking Info -->
            <td style="width:50%; padding-left:10px;">
                <div class="section">
                    <div class="section-title">Booking Details</div>
                    <table class="info-table">
                        <tr><td class="label">Package</td><td class="value">{{ $packageBooking->healthCheck->name }}</td></tr>
                        <tr><td class="label">Payment Type</td><td class="value">{{ $packageBooking->payment_type }}</td></tr>
                        <tr><td class="label">Amount Paid</td><td class="value">{{ $packageBooking->amount_paid }}</td></tr>
                        <tr><td class="label">Total Amount</td><td class="value">{{ $packageBooking->total_amount }}</td></tr>
                        <tr><td class="label">Status</td><td class="value">{{ ucfirst($packageBooking->status) }}</td></tr>
                        <tr><td class="label">Booked On</td><td class="value">{{ \Carbon\Carbon::parse($packageBooking->created_at)->format('F j, Y') }}</td></tr>
                    </table>
                </div>
            </td>
        </tr>
    </table>

    <!-- Important Note -->
    <div class="note">
        <strong>Important:</strong> Please carry this receipt (printed or digital) and a valid ID. Reach the reception 15 minutes prior to your slot for registration.
    </div>

    <!-- Footer -->
    <div class="footer">
        <div>Document generated on {{ now()->format('F j, Y \a\t g:i A') }} <span class="nowrap">(Booking: {{ $packageBooking->id }})</span></div>
        <div>&copy; {{ date('Y') }} Xet Specialized Hospital. All rights reserved.</div>
    </div>

</div>
</body>
</html>
