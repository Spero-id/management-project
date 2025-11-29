<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delivery Order - {{ $deliveryOrder->do_number }}</title>
    <style>
        body {
            font-family: DejaVu Sans, Arial, Helvetica, sans-serif;
            font-size: 12px;
            color: #222;
            margin: 0;
            padding: 20px;
        }

        .header {
            display: table;
            width: 100%;
            margin-bottom: 30px;
            padding-bottom: 15px;
        }

        .header-left {
            display: table-cell;
            width: 15%;
            vertical-align: top;
        }

        .header-center {
            display: table-cell;
            width: 50%;
            vertical-align: top;
            padding-left: 15px;
        }

        .header-right {
            display: table-cell;
            width: 35%;
            vertical-align: top;
            text-align: right;
        }

        .logo {
            margin-bottom: 16px;
        }

        .company-name {
            font-size: 18px;
            font-weight: bold;
            color: #333;
            margin-bottom: 5px;
        }

        .company-tagline {
            font-size: 10px;
            color: #FF9800;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .company-services {
            font-size: 12px;
            color: #666;
            margin-bottom: 15px;
            font-style: italic;
        }

        .company-address {
            font-size: 7px;
            color: #555;
            line-height: 1.4;
        }

        .do-title {
            font-size: 18px;
            font-weight: bold;
            color: #666;
            margin-bottom: 20px;
        }

        .do-info {
            font-size: 12px;
            color: #333;
            line-height: 1.6;
        }

        .do-info strong {
            display: inline-block;
            width: 80px;
        }

        .greeting {
            margin-bottom: 20px;
            font-size: 12px;
            color: #333;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .info-table td {
            padding: 4px 8px;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .items-table th,
        .items-table td {
            border: 1px solid #aaa;
            padding: 6px 8px;
        }

        .items-table th {
            background: #f5f5f5;
        }

        .text-end {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .notes {
            background: #f8f9fa;
            border: 1px solid #eee;
            padding: 10px;
            border-radius: 4px;
            margin-top: 10px;
        }

        @media print {
            body {
                margin: 0;
                padding: 15px;
            }
        }
    </style>
</head>

<body>
    <div class="header">
        <div class="header-center">
            <div class="logo">
                <img src="{{ public_path('/assets/images/SIS-Logo-NB.png') }}" alt="SISOLUSI"
                    style="width:140px; height:auto;">
            </div>
            <div class="company-name">PT Smart Integrator Solution</div>
            <div class="company-tagline">"OUR TRUE ALL-IN ONE STOP SOLUTION PROVIDER"</div>
            <div class="company-services">Audio Visual - System Integrator - Design Interior - Creative Design</div>
            <div class="company-address">
                Sing Asri Plaza2, Blok A1 No.23<br>
                Jl.Merpati Raya<br>
                Ciputat<br>
                Tangerang Selatan 15413<br>
                Email: <span style="color: #2196F3;">aspan@sisolusi.com</span><br>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span
                    style="color: #2196F3;">aspan.bagus@gmail.com</span>
            </div>
        </div>
        <div class="header-right">
            <div class="do-title">Delivery Order</div>
            <div class="do-info">
                <span style="font-weight:bold">Date</span>: {{ $deliveryOrder->created_at->format('d F Y') }}<br>
                <span style="font-weight:bold">NO Delivery Order</span>: {{ $deliveryOrder->do_number }}<br>
                <span style="font-weight:bold">No PO</span>: {{ $deliveryOrder->project->no_po ?? 'N/A' }}<br>
            </div>
        </div>
    </div>

    <div class="greeting">
        <strong>Dear Customer,</strong>
    </div>

    <!-- Delivery Items Table -->
    <table class="items-table">
        <thead>
            <tr>
                <th style="width: 5%; text-align: center;">NO</th>
                <th style="width: 35%;">ITEM DESCRIPTION</th>
                <th style="width: 15%;">BRAND - TYPE</th>
                <th style="width: 25%;">SERIAL NUMBER</th>
                <th style="width: 8%; text-align: center;">QTY</th>
                <th style="width: 12%; text-align: center;">NOTES</th>
            </tr>
        </thead>
        <tbody>
            @if ($deliveryOrder->items->count() > 0)
                @foreach ($deliveryOrder->items as $index => $item)
                    <tr>
                        <td style="text-align: center;">{{ $index + 1 }}</td>
                        <td style="font-size: 10px; text-align: left;">
                            {{ $item->product->name ?? 'N/A' }}
                        </td>
                        <td style="font-size: 10px; text-align: left;">
                            {{ ($item->product->brand ?? 'N/A') . ' - ' . ($item->product->type ?? 'N/A') }}
                        </td>
                        <td style="font-size: 9px; text-align: left; line-height: 1.2;">
                            @if ($item->sn && is_array($item->sn))
                                @foreach ($item->sn as $serialNumber)
                                    S/N: {{ $serialNumber }}<br>
                                @endforeach
                            @else
                                -
                            @endif
                        </td>
                        <td style="text-align: center;">{{ number_format($item->qty, 0) }}</td>
                        <td style="text-align: center; font-size: 10px;">{{ $item->notes }}</td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="6" style="text-align:center; padding:20px; color:#888;">No items found</td>
                </tr>
            @endif
        </tbody>
    </table>

    <!-- Additional Notes Section -->
    @if ($deliveryOrder->notes)
        <div class="notes">
            <strong>Delivery Notes:</strong><br>
            {{ $deliveryOrder->notes }}
        </div>
    @endif

    <!-- Signature Section -->
    <div style="margin-top: 40px;">
        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <td style="width: 33%; text-align: center; vertical-align: top; padding: 10px;">
                    <div style="margin-bottom: 60px;"><strong>Delivered by</strong></div>
                    <div style="border-top: 1px solid #333; padding-top: 5px;">
                        <strong>{{ $deliveryOrder->user->name ?? 'Admin' }}</strong><br>
                        <small>PT Smart Integrator Solution</small>
                    </div>
                </td>
                <td style="width: 33%; text-align: center; vertical-align: top; padding: 10px;">
                    <div style="margin-bottom: 60px;"><strong>Received by</strong></div>
                    <div style="border-top: 1px solid #333; padding-top: 5px;">
                        <strong>_____________________</strong><br>
                        <small>Name & Signature</small>
                    </div>
                </td>
                <td style="width: 33%; text-align: center; vertical-align: top; padding: 10px;">
                    <div style="margin-bottom: 60px;"><strong>Date & Time</strong></div>
                    <div style="border-top: 1px solid #333; padding-top: 5px;">
                        <strong>{{ now()->format('d/m/Y H:i') }}</strong>
                    </div>
                </td>
            </tr>
        </table>
    </div>

</body>

</html>
