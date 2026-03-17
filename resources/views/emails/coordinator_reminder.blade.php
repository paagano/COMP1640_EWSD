<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Contribution Review Reminder</title>
</head>

<body style="font-family: Arial, sans-serif; background-color: #f4f6f9; padding: 20px;">

<div style="max-width: 600px; margin: auto; background: #ffffff; padding: 20px; border-radius: 8px;">

    <h2 style="color: #0d6efd; margin-bottom: 20px;">
        Contribution Review Reminder
    </h2>

    <p>
        Dear <strong>{{ $contribution->faculty->coordinator->name }}</strong>,
    </p>

    <p>
        A student contribution is currently awaiting your review in the
        <strong>UoG Annual Magazine System</strong>.
    </p>


    <table style="width:100%; margin-top:15px;">
        <tr>
            <td style="padding:6px 0;"><strong>Title:</strong></td>
            <td>{{ $contribution->title }}</td>
        </tr>

        <tr>
            <td style="padding:6px 0;"><strong>Student:</strong></td>
            <td>{{ $contribution->student->name }}</td>
        </tr>

        <tr>
            <td style="padding:6px 0;"><strong>Faculty:</strong></td>
            <td>{{ $contribution->faculty->name }}</td>
        </tr>

        <tr>
            <td style="padding:6px 0;"><strong>Date Submitted:</strong></td>
            <td>{{ $contribution->created_at->format('d M Y') }}</td>
        </tr>

        <tr>
            <td style="padding:6px 0;"><strong>Days Pending:</strong></td>
            <td>{{ $daysPending }}</td>
        </tr>
    </table>


    {{-- Friendly Reminder --}}
    @if($type == 'friendly')

        <div style="margin-top:20px; padding:12px; background:#e7f1ff; border-left:4px solid #0d6efd; border-radius:4px;">
            This is a friendly reminder to review the contribution and provide your comments.
        </div>

    @endif


    {{-- SLA Warning --}}
    @if($type == 'warning')

        <div style="margin-top:20px; padding:12px; background:#fff3cd; border-left:4px solid #ffc107; border-radius:4px;">
            <strong>SLA Warning:</strong> The 14-day review deadline is approaching.
            Please log into the system and submit your comments soon.
        </div>

    @endif


    {{-- SLA Breach --}}
    @if($type == 'breach')

        <div style="margin-top:20px; padding:12px; background:#f8d7da; border-left:4px solid #dc3545; border-radius:4px;">
            <strong>SLA Breach Detected.</strong><br>
            The 14-day review deadline has passed. Immediate action is required.
        </div>

    @endif


    <p style="margin-top:25px;">
        Please log into the system to review this contribution.
    </p>


    <div style="margin-top:20px;">
        <a href="{{ url('/') }}"
           style="background:#0d6efd; color:white; padding:10px 15px; text-decoration:none; border-radius:4px;">
            Access System
        </a>
    </div>


    <p style="margin-top:30px;">
        Regards,<br>
        <strong>UoG Annual Magazine Team</strong>
    </p>


    <p style="margin-top:30px; font-size: 12px; color: #777;">
        This is an automated notification from the UoG Annual Magazine System.
        Please do not reply to this email.
    </p>

</div>

</body>
</html>