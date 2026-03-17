<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Contribution Status Update</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f6f9; padding: 20px;">

    <div style="max-width: 600px; margin: auto; background: #ffffff; padding: 25px; border-radius: 8px;">

        <h2 style="margin-bottom: 20px;">
            Contribution Status Update
        </h2>

        <p>
            Dear {{ $contribution->student->name }},
        </p>

        <p>
            Your submission titled:
        </p>

        <p style="font-weight: bold; font-size: 16px;">
            "{{ $contribution->title }}"
        </p>

        <p>
            has been reviewed by your Faculty Marketing Coordinator.
        </p>

        @if($contribution->status === 'selected')
            <div style="background-color: #d4edda; padding: 10px; border-radius: 5px; color: #155724;">
                <strong>Status:</strong> Selected for Publication 🎉
            </div>
        @elseif($contribution->status === 'rejected')
            <div style="background-color: #f8d7da; padding: 10px; border-radius: 5px; color: #721c24;">
                <strong>Status:</strong> Not Selected
            </div>
        @else
            <div style="background-color: #fff3cd; padding: 10px; border-radius: 5px; color: #856404;">
                <strong>Status:</strong> Reviewed
            </div>
        @endif

        <h4 style="margin-top: 20px;">Coordinator Feedback:</h4>

        <div style="background-color: #f1f1f1; padding: 15px; border-radius: 5px;">
            {{ $commentText }}
        </div>

        <p style="margin-top: 25px;">
            You may log in to the system for further details.
        </p>

        <a href="{{ url('/') }}"
           style="display:inline-block; margin-top:15px; padding:10px 15px; background:#0d6efd; color:white; text-decoration:none; border-radius:5px;">
            Access System
        </a>

        <p style="margin-top: 30px; font-size: 12px; color: #777;">
            This is an automated notification from the UoG Annual Magazine System. Please do not reply to this email.
        </p>

    </div>

</body>
</html>