<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Selected Contributions</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th, td {
            border: 1px solid #000;
            padding: 6px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        h2 {
            text-align: center;
        }
    </style>
</head>
<body>

    <h2>Contributions Selected fo Publication</h2>

    <table>
        <thead>
            <tr>
                <th>Title</th>
                <th>Student</th>
                <th>Faculty</th>
                <th>Status</th>
                <th>Selected On</th>
            </tr>
        </thead>
        <tbody>
            @foreach($contributions as $contribution)
                <tr>
                    <td>{{ $contribution->title }}</td>
                    <td>{{ $contribution->student->name ?? '' }}</td>
                    <td>{{ $contribution->faculty->name ?? '' }}</td>
                    <td>{{ ucfirst($contribution->status) }}</td>
                    <td>{{ $contribution->selected_at }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>