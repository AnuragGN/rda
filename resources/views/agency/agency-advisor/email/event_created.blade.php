<!DOCTYPE html>
<html>
<head>
    <title>New Event Task Created</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 1076px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #333;
        }
        p {
            margin-bottom: 10px;
            line-height: 1.5;
        }
        .email-greeting {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        
        <p class="email-greeting">Hi,</p>
        {{-- <p>We are pleased to inform you that new event has been created:</p> --}}
        {{-- <p><strong>Subject:</strong> {{ $emailData['subject'] }}</p> --}}
        <p><strong>Description:</strong> {!! $emailData['description'] !!}</p>
        
        <!-- Add any other email content here as needed -->
        
        <p>Thank you!</p>
    </div>
</body>
</html>
