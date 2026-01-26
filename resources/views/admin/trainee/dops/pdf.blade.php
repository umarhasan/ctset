<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Trainee DOPS Report</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #333; padding: 5px; text-align: left; }
        th { background-color: #eee; }
        .nowrap { white-space: nowrap; }
    </style>
</head>
<body>
    <h3>Trainee DOPS Report</h3>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Trainee</th>
                <th>Rotation</th>
                <th>DOPS</th>
                <th>Date</th>
                <th>From Time</th>
                <th>To Time</th>
                <th>Diagnosis</th>
                <th>Procedure</th>
                <th>Comments</th>
                <th>Status</th>
                <th>Created At</th>
            </tr>
        </thead>
        <tbody>
            @foreach($attempts as $a)
                <tr>
                    <td>{{ $a->id }}</td>
                    <td>{{ $a->trainee->name ?? '-' }}</td>
                    <td>{{ $a->rotation->title ?? '-' }}</td>
                    <td>{{ $a->dops->title ?? '-' }}</td>
                    <td class="nowrap">{{ $a->date }}</td>
                    <td class="nowrap">{{ $a->from_time }}</td>
                    <td class="nowrap">{{ $a->to_time ?? '-' }}</td>
                    <td>
                        @if($a->diagnosis)
                            {{ collect(json_decode($a->diagnosis))
                                ->map(fn($d) => $d->name.': '.$d->value)
                                ->implode(' | ') }}
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        @if($a->procedure)
                            {{ collect(json_decode($a->procedure))
                                ->map(fn($p) => $p->name.': '.$p->value)
                                ->implode(' | ') }}
                        @else
                            -
                        @endif
                    </td>
                    <td>{{ $a->comments ?? '-' }}</td>
                    <td>{{ $a->status }}</td>
                    <td class="nowrap">{{ $a->created_at->format('Y-m-d H:i') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
