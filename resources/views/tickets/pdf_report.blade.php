<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>GayaCare Monthly Report</title>
    <style>
        /* 1. MAP THE LOCAL FILES TO THE FONT FAMILY */
        @font-face {
            font-family: 'Instrument Sans';
            src: url('{{ public_path('fonts/InstrumentSans-Regular.ttf') }}') format('truetype');
            font-weight: 400;
            font-style: normal;
        }
        @font-face {
            font-family: 'Instrument Sans';
            src: url('{{ public_path('fonts/InstrumentSans-Bold.ttf') }}') format('truetype');
            font-weight: 700;
            font-style: normal;
        }
        @font-face {
            font-family: 'Instrument Sans';
            src: url('{{ public_path('fonts/InstrumentSans-Bold.ttf') }}') format('truetype');
            font-weight: 800; /* Map 800 to the bold file as well for consistency */
            font-style: normal;
        }

        /* 2. GLOBAL RESET: MANDATORY FOR FONT UNIFORMITY */
        @page { margin: 0; }

        * {
            font-family: 'Instrument Sans', sans-serif !important;
            -webkit-font-smoothing: antialiased;
        }

        body {
            color: #1e293b;
            margin: 0;
            padding: 45px;
            font-size: 11px;
            line-height: 1.4;
            background-color: #ffffff;
            font-family: 'Instrument Sans', sans-serif !important;
        }

        /* 3. CORPORATE HEADER (Matching System UI) */
        .header-container {
            border-bottom: 4px solid #4f46e5;
            padding-bottom: 20px;
            margin-bottom: 35px;
        }
        .report-title {
            font-size: 28px;
            font-weight: 800; /* Uses Bold TTF */
            color: #0f172a;
            text-transform: uppercase;
            letter-spacing: -1px;
            margin: 0;
            line-height: 1;
        }
        .meta-table { width: 100%; margin-top: 15px; }
        .meta-table td { padding: 0; font-size: 10px; color: #64748b; font-weight: 400; }

        /* 4. SUMMARY BOXES (Consistent Geometric Look) */
        .summary-grid {
            width: 100%;
            margin-bottom: 40px;
            border-collapse: collapse;
        }
        .summary-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            padding: 22px 10px;
            text-align: center;
            width: 20%;
        }
        .summary-box .label {
            font-size: 8px;
            font-weight: 800;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 8px;
            display: block;
        }
        .summary-box .value {
            font-size: 26px;
            font-weight: 800;
            color: #0f172a;
            line-height: 1;
        }

        .border-total { border-top: 4px solid #4f46e5; }
        .border-open { border-top: 4px solid #16a34a; }
        .border-assigned { border-top: 4px solid #2563eb; }
        .border-hold { border-top: 4px solid #eab308; }
        .border-resolved { border-top: 4px solid #94a3b8; }

        /* 5. CHART AREA */
        .charts-section {
            background-color: #f8fafc;
            padding: 30px;
            border-radius: 20px;
            text-align: center;
            margin-bottom: 30px;
            border: 1px solid #e2e8f0;
        }
        .charts-image {
            width: 100%;
            max-height: 420px;
            object-fit: contain;
        }

        /* 6. DATA TABLE */
        .data-table {
            width: 100%;
            border-collapse: collapse;
        }
        .data-table th {
            background-color: #0f172a;
            color: #ffffff;
            font-size: 9px;
            font-weight: 800;
            text-transform: uppercase;
            padding: 14px 10px;
            text-align: left;
        }
        .data-table td {
            padding: 14px 10px;
            border-bottom: 1px solid #f1f5f9;
            color: #334155;
            font-size: 10px;
        }
        .data-table tr:nth-child(even) { background-color: #fcfdfe; }
        .text-bold { font-weight: 800; }

        /* 7. FOOTER */
        .footer {
            position: fixed;
            bottom: 25px;
            left: 45px;
            right: 45px;
            border-top: 1px solid #e2e8f0;
            padding-top: 12px;
            text-align: center;
            font-size: 8px;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            font-weight: 800;
        }
        .page-break { page-break-before: always; }
    </style>
</head>
<body>

    <div class="footer">
        GayaCare Support System — Page <span class="pagenum"></span>
    </div>

    <div class="header-container">
        <h1 class="report-title">Monthly Performance Report</h1>
        <table class="meta-table">
            <tr>
                <td style="width: 55%;">
                    Reporting Period: <span class="text-bold">{{ $targetDate->format('F Y') }}</span><br>
                    Department: <span class="text-bold">ICT Support Unit</span>
                </td>
                <td style="text-align: right;">
                    Generated On: <span class="text-bold">{{ now()->format('d M Y, h:i A') }}</span><br>
                    Identity: <span class="text-bold">GayaCare Support</span>
                </td>
            </tr>
        </table>
    </div>

    <table class="summary-grid">
        <tr>
            <td class="summary-box border-total">
                <span class="label">Total Tickets</span>
                <span class="value">{{ $stats['total'] }}</span>
            </td>
            <td class="summary-box border-open">
                <span class="label">Open</span>
                <span class="value" style="color: #16a34a;">{{ $stats['open'] }}</span>
            </td>
            <td class="summary-box border-assigned">
                <span class="label">Assigned</span>
                <span class="value" style="color: #2563eb;">{{ $stats['assigned'] }}</span>
            </td>
            <td class="summary-box border-hold">
                <span class="label">On Hold</span>
                <span class="value" style="color: #ca8a04;">{{ $stats['on_hold'] }}</span>
            </td>
            <td class="summary-box border-resolved">
                <span class="label">Resolved</span>
                <span class="value" style="color: #64748b;">{{ $stats['resolved'] }}</span>
            </td>
        </tr>
    </table>

    <div class="charts-section">
        <p style="text-align: left; font-weight: 800; color: #475569; font-size: 10px; text-transform: uppercase; margin-bottom: 20px; letter-spacing: 1.5px;">Analytical Overview</p>
        <img src="{{ $imageData }}" class="charts-image">
    </div>

    <div class="page-break"></div>

    <div class="header-container" style="border-bottom-width: 2px;">
        <h2 class="report-title" style="font-size: 18px;">Detailed Audit Log</h2>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 45px;">ID</th>
                <th>Complainant</th>
                <th>Subject</th>
                <th>Category</th>
                <th style="width: 55px;">Priority</th>
                <th style="width: 65px;">Status</th>
                <th style="width: 75px;">Date</th>
            </tr>
        </thead>
        <tbody>
            @forelse($monthlyTickets as $ticket)
            <tr>
                <td class="text-bold" style="color: #4f46e5;">#{{ $ticket->id }}</td>
                <td class="text-bold">{{ $ticket->reporter_name }}</td>
                <td>{{ $ticket->title }}</td>
                <td>{{ $ticket->category }}</td>
                <td>
                    <span class="text-bold" style="font-size: 8px; color: {{ $ticket->priority == 'High' ? '#be123c' : ($ticket->priority == 'Medium' ? '#d97706' : '#059669') }}">
                        {{ $ticket->priority }}
                    </span>
                </td>
                <td class="text-bold">{{ $ticket->status }}</td>
                <td>{{ $ticket->created_at->format('d/m/Y') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align: center; padding: 50px; color: #94a3b8; font-weight: 700;">No analytical records found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>
