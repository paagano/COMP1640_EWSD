<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>New Contribution Submitted</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f6f9; padding: 20px;">

    <div style="max-width: 600px; margin: auto; background: #ffffff; padding: 20px; border-radius: 8px;">

        <h2 style="color: #0d6efd; margin-bottom: 20px;">
            New Magazine Contribution Submitted
        </h2>

        <p>
            A new article has been submitted to the UoG Annual Magazine System.
        </p>

        <table style="width:100%; margin-top:15px;">
            <tr>
                <td><strong>Title:</strong></td>
                <td>{{ $contribution->title }}</td>
            </tr>
            <tr>
                <td><strong>Student:</strong></td>
                <td>{{ $contribution->student->name }}</td>
            </tr>
            <tr>
                <td><strong>Faculty:</strong></td>
                <td>{{ $contribution->faculty->name }}</td>
            </tr>
            <tr>
                <td><strong>Academic Year:</strong></td>
                <td>{{ $contribution->academicYear->year_name }}</td>
            </tr>
        </table>

        <p style="margin-top:20px;">
            Please log into the system and provide your review and comments within 14 days.
        </p>

        <div style="margin-top:25px;">
            <a href="{{ url('/') }}"
               style="background:#0d6efd; color:white; padding:10px 15px; text-decoration:none; border-radius:4px;">
                Access System
            </a>
        </div>

        <p style="margin-top:30px; font-size: 12px; color: #777;">
            This is an automated notification from the UoG Annual Magazine System. Please do not reply to this email.
        </p>

    </div>

</body>
</html>