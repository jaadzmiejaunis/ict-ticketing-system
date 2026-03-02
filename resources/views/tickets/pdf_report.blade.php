<!DOCTYPE html>
<html>
<head>
    <title>Monthly ICT Report</title>
    <style>
        body { font-family: sans-serif; }
        .header { text-align: center; margin-bottom: 30px; }
        .stats-box { border: 1px solid #ddd; padding: 10px; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; font-size: 12px; }
        th { background-color: #f2f2f2; }
        .badge { padding: 2px 6px; border-radius: 4px; color: white; font-size: 10px; }
        .bg-red { background-color: #ef4444; }
        .bg-green { background-color: #22c55e; }
        .bg-gray { background-color: #9ca3af; }
    </style>
</head>
<body>

    <div class="header">
        <h2>ICT Monthly Complaint Report</h2>
        <p>Generated on: {{ now()->format('d M Y') }}</p>
    </div>

    <div class="stats-box">
        <h3>Summary for {{ now()->format('F Y') }}</h3>
        <p><strong>Total Tickets:</strong> {{ $stats['total'] }}</p>
        <p><strong>Open Issues:</strong> {{ $stats['open'] }}</p>
        <p><strong>Resolved Issues:</strong> {{ $stats['resolved'] }}</p>
        <p><strong>High Priority Cases:</strong> {{ $stats['high'] }}</p>
    </div>

    <h3>Detailed Ticket Log</h3>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Reporter</th>
                <th>Issue</th>
                <th>Category</th>
                <th>Priority</th>
                <th>Status</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($monthlyTickets as $ticket)
            <tr>
                <td>#{{ $ticket->id }}</td>
                <td>{{ $ticket->reporter_name }}</td>
                <td>{{ $ticket->title }}</td>
                <td>{{ $ticket->category }}</td>
                <td>
                    <span class="badge {{ $ticket->priority == 'High' ? 'bg-red' : ($ticket->priority == 'Low' ? 'bg-green' : 'bg-gray') }}">
                        {{ $ticket->priority }}
                    </span>
                </td>
                <td>{{ $ticket->status }}</td>
                <td>{{ $ticket->created_at->format('d/m/Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
