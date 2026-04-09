<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>GayaCare Monthly Report</title>
    <style>
        /* 1. COMPREHENSIVE FONT DEFINITIONS (Instrument Sans) */
        @font-face {
            font-family: 'Instrument Sans';
            font-style: normal;
            font-weight: 400;
            src: url(https://fonts.gstatic.com/s/instrumentsans/v1/9XUh8at6W5E5_hGRzS6GrS9E9WdfG0_l.ttf) format('truetype');
        }
        @font-face {
            font-family: 'Instrument Sans';
            font-style: normal;
            font-weight: 600;
            src: url(https://fonts.gstatic.com/s/instrumentsans/v1/9XUh8at6W5E5_hGRzS6G0S9E9WdfG0_l.ttf) format('truetype');
        }
        @font-face {
            font-family: 'Instrument Sans';
            font-style: normal;
            font-weight: 800;
            src: url(https://fonts.gstatic.com/s/instrumentsans/v1/9XUh8at6W5E5_hGRzS6G6S9E9WdfG0_l.ttf) format('truetype');
        }

        /* 2. GLOBAL RESET */
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
            background-color: #fff;
        }

        /* 3. CORPORATE HEADER (Refined consistency) */
        .header-container {
            border-bottom: 4px solid #4f46e5;
            padding-bottom: 20px;
            margin-bottom: 35px;
        }
        .report-title {
            font-size: 28px;
            font-weight: 800;
            color: #0f172a;
            text-transform: uppercase;
            letter-spacing: -0.8px; /* Tight tracking matching system UI */
            margin: 0;
            line-height: 1;
        }
        .meta-table { width: 100%; margin-top: 15px; }
        .meta-table td { padding: 0; font-size: 10px; color: #64748b; font-weight: 400; }

        /* 4. SUMMARY GRID (The "Total Tickets" Row) */
        .summary-grid {
            width: 100%;
            margin-bottom: 40px;
            border-collapse: separate;
            border-spacing: 0;
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
            letter-spacing: 1.2px;
            margin-bottom: 8px;
            display: block;
        }
        .summary-box .value {
            font-size: 26px;
            font-weight: 800;
            color: #0f172a;
            line-height: 1;
        }

        /* Specific status colors */
        .border-total { border-top: 4px solid #4f46e5; }
        .border-open { border-top: 4px solid #16a34a; }
        .border-assigned { border-top: 4px solid #2563eb; }
        .border-hold { border-top: 4px solid #eab308; }
        .border-resolved { border-top: 4px solid #94a3b8; }

        /* 5. CHART SECTION (Matching Graph font labels) */
        .charts-section {
            background-color: #f8fafc;
            padding: 30px;
            border-radius: 20px;
            text-align: center;
            margin-bottom: 30px;
            border: 1px solid #e2e8f0;
        }
        .section-label {
            text-align: left;
            font-weight: 800;
            color: #475569;
            font-size: 11px;
            text-transform: uppercase;
            margin-bottom: 20px;
            letter-spacing: 1.5px;
            margin-top: 0;
        }
        .charts-image {
            width: 100%;
            max-height: 440px;
            object-fit: contain;
        }

        /* 6. DETAILED DATA TABLE */
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
            letter-spacing: 0.8px;
            padding: 14px 12px;
            text-align: left;
        }
        .data-table td {
            padding: 14px 12px;
            border-bottom: 1px solid #f1f5f9;
            color: #334155;
            font-size: 10px;
            font-weight: 400;
        }
        .data-table tr:nth-child(even) { background-color: #fcfdfe; }
        .text-bold { font-weight: 800; }
        .text-indigo { color: #4f46e5; }

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
                    Reporting Period: <span class="text-bold" style="color: #0f172a;">{{ $targetDate->format('F Y') }}</span><br>
                    Department: <span class="text-bold" style="color: #0f172a;">ICT Support Unit</span>
                </td>
                <td style="text-align: right;">
                    Generated On: <span class="text-bold" style="color: #0f172a;">{{ now()->format('d M Y, h:i A') }}</span><br>
                    Identity: <span class="text-bold" style="color: #0f172a;">GayaCare Support</span>
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
        <h2 class="section-label">Analytical Overview</h2>
        <img src="{{ $imageData }}" class="charts-image" alt="Visual Analytics">
    </div>

    <div class="page-break"></div>

    <div class="header-container" style="border-bottom-width: 2px;">
        <h2 class="report-title" style="font-size: 18px;">Detailed Audit Log</h2>
        <p style="margin: 5px 0 0; color: #64748b; font-size: 10px; font-weight: 400;">Comprehensive record of reported issues for {{ $targetDate->format('F Y') }}</p>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 45px;">ID</th>
                <th>Complainant</th>
                <th>Issue Subject</th>
                <th>Category</th>
                <th style="width: 55px;">Priority</th>
                <th style="width: 65px;">Status</th>
                <th style="width: 75px;">Logged Date</th>
            </tr>
        </thead>
        <tbody>
            @forelse($monthlyTickets as $ticket)
            <tr>
                <td class="text-bold text-indigo">#{{ $ticket->id }}</td>
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
                <td colspan="7" style="text-align: center; padding: 50px; color: #94a3b8; font-weight: 600;">No analytical data found for this reporting cycle.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>
