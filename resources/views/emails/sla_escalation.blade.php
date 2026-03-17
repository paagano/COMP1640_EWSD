<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>SLA Breach Escalation</title>
</head>

<body style="font-family: Arial, sans-serif; background-color: #f4f6f9; padding: 20px;">

<div style="max-width: 600px; margin: auto; background: #ffffff; padding: 20px; border-radius: 8px;">

    <h2 style="color: #dc3545; margin-bottom: 20px;">
        SLA Breach Escalation
    </h2>

    <p>
        Dear <strong>Marketing Manager</strong>,
    </p>

    <p>
        A contribution in the <strong>UoG Annual Magazine System</strong> has exceeded the 
        <strong>14-day review Service Level Agreement (SLA)</strong>.
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
            <td style="padding:6px 0;"><strong>Faculty Coordinator:</strong></td>
            <td>{{ $coordinator->name }}</td>
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


    <div style="margin-top:20px; padding:12px; background:#f8d7da; border-left:4px solid #dc3545; border-radius:4px;">
        <strong>SLA Breach Detected.</strong><br>
        The assigned Faculty Coordinator has not reviewed this submission within the required 14-day period.
    </div>


    <p style="margin-top:20px;">
        Please review the situation and follow up with the Faculty Coordinator to ensure the submission is reviewed promptly.
    </p>


    <div style="margin-top:25px;">
        <a href="{{ url('/') }}"
           style="background:#dc3545; color:white; padding:10px 15px; text-decoration:none; border-radius:4px;">
            Access System
        </a>
    </div>


    <p style="margin-top:30px;">
        Regards,<br>
        <strong>UoG Annual Magazine Team</strong>
    </p>


    <p style="margin-top:30px; font-size: 12px; color: #777;">
        This is an automated notification from the UoG Annual Magazine System. Please do not reply to this email.
    </p>

</div>

</body>
</html>