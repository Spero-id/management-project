<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quotation PDF</title>
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

        .quotation-title {
            font-size: 24px;
            font-weight: bold;
            color: #666;
            margin-bottom: 20px;
        }

        .quotation-info {
            font-size: 12px;
            color: #333;
            line-height: 1.6;
        }

        .quotation-info strong {
            display: inline-block;
            width: 50px;
        }

        .title {
            font-size: 22px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .subtitle {
            font-size: 14px;
            color: #888;
            margin-bottom: 20px;
        }

        .info-table,
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .info-table td {
            padding: 4px 8px;
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

        .text-success {
            color: #198754;
        }

        .badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 4px;
            color: #fff;
            font-size: 11px;
        }

        .bg-secondary {
            background: #6c757d;
        }

        .bg-info {
            background: #0dcaf0;
        }

        .bg-success {
            background: #198754;
        }

        .bg-danger {
            background: #dc3545;
        }

        .notes {
            background: #f8f9fa;
            border: 1px solid #eee;
            padding: 10px;
            border-radius: 4px;
            margin-top: 10px;
        }

        .greeting {
            margin-bottom: 20px;
            font-size: 12px;
            color: #333;
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
            <div class="quotation-title">Quotation - IFP</div>
            <div class="quotation-info">
                <strong>Date</strong> : {{ $quotation->created_at->format('d F Y') }}<br>
                <strong>No</strong> : {{ $quotation->quotation_number }}<br>

            </div>
        </div>
    </div>

    <div class="greeting">
        <strong>Dear Customer,</strong><br>
        Herewith we send you the Price Quotation For
    </div>




    <!-- Combined Items Table -->
    <table class="items-table">
        <thead>
            <tr>
                <th style="width: 5%; text-align: center;">NO</th>
                <th style="width: 45%;">URAIAN</th>
                <th style="width: 15%;">JENIS BARANG</th>
                <th style="width: 8%; text-align: center;">VOL</th>
                <th style="width: 8%; text-align: center;">UNIT</th>
                <th style="width: 10%; text-align: right;">HARGA SATUAN</th>
                <th style="width: 9%; text-align: right;">TOTAL HARGA</th>
            </tr>
        </thead>
        <tbody>
            @php $itemNumber = 1; @endphp

            @if ($quotation->items->count() > 0)
                <tr>
                    <td colspan="7" style="background-color: #f5f5f5; font-weight: bold; text-align: center;">PRODUCT
                        ITEMS</td>
                </tr>
                @foreach ($quotation->items as $item)
                    <tr>
                        <td style="text-align: center;">{{ $itemNumber++ }}</td>
                        <td>
                            {{ $item->product->name }}
                            @if ($item->product->description)
                                <div style="color:#888; font-size:11px;">{{ $item->product->description }}</div>
                            @endif
                        </td>
                        <td>{{ $item->product->brand ?? 'PRODUCT' }}</td>
                        <td style="text-align: center;">{{ number_format($item->quantity, 0) }}</td>
                        <td style="text-align: center;">Unit</td>
                        <td style="text-align: right;">Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                        <td style="text-align: right;">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            @endif

            @if ($quotation->installationItems->count() > 0)
                <tr>
                    <td colspan="7" style="background-color: #f5f5f5; font-weight: bold; text-align: center;">
                        INSTALLATION</td>
                </tr>
                @foreach ($quotation->installationItems as $item)
                    <tr>
                        <td style="text-align: center;">{{ $itemNumber++ }}</td>
                        <td>
                            {{ $item->installation->name }}
                            @if ($item->installation->description)
                                <div style="color:#888; font-size:11px;">{{ $item->installation->description }}</div>
                            @endif
                            @if ($item->installation->proportional)
                                <div style="color:#666; font-size:11px;">
                                    <em>Proportional: {{ number_format($item->installation->proportional, 2) }}%</em>
                                </div>
                            @endif
                        </td>
                        <td>INSTALLATION</td>
                        <td style="text-align: center;">{{ number_format($item->quantity, 0) }}</td>
                        <td style="text-align: center;">Unit</td>
                        <td style="text-align: right;">Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                        <td style="text-align: right;">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            @endif

            @if ($quotation->accommodationItems->count() > 0)
                <tr>
                    <td colspan="7" style="background-color: #f5f5f5; font-weight: bold; text-align: center;">
                        ACCOMMODATION</td>
                </tr>
                @foreach ($quotation->accommodationItems as $item)
                    <tr>
                        <td style="text-align: center;" >{{ $itemNumber++ }}</td>
                        <td colspan="4">
                            {{ $item->name }}
                       
                        </td>
                        <td style="text-align: right;">Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                        <td style="text-align: right;">Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            @endif

            @if (
                $quotation->items->count() == 0 &&
                    $quotation->installationItems->count() == 0 &&
                    $quotation->accommodationItems->count() == 0)
                <tr>
                    <td colspan="7" style="text-align:center; padding:20px; color:#888;">No items found</td>
                </tr>
            @endif
        </tbody>
        @if (
            $quotation->items->count() > 0 ||
                $quotation->installationItems->count() > 0 ||
                $quotation->accommodationItems->count() > 0)
            <tfoot>
                <tr style="border-top: 2px solid #000;">
                    <td colspan="6" class="text-end"
                        style="font-weight:bold; font-size:14px; background-color: #e9ecef;">Total</td>
                    <td class="text-end" style="font-size:14px; font-weight:bold; background-color: #e9ecef;">
                        Rp {{ number_format($quotation->total_amount, 0, ',', '.') }}
                    </td>
                </tr>
            </tfoot>
        @endif
    </table>


    @if ($quotation->notes)
        <div class="notes">
            <strong>Notes:</strong><br>
            {{ $quotation->notes }}
        </div>
    @endif

    <!-- Signature Section -->
    <div style="margin-top: 40px;">
        <div style="margin-bottom: 10px;">
            <strong>Thank & Regards</strong>
        </div>

        <div style="margin-top: 60px; margin-bottom: 10px;">
            <!-- Space for signature -->
        </div>

        <div>
            <strong>Name:</strong><br>
            {{ $quotation->user->name ?? 'Admin' }}
        </div>
    </div>


</body>

</html>
