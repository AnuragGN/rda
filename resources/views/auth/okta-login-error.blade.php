<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel='icon' href=/ma/images/ntc/logo-16x16.png type='image/x-icon'/ >
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Northern Trust SSO Login Error</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            margin: 100px auto;
        }
        .error-container {
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
            max-width: 400px;
            margin: auto;
            background-color: #f9f9f9;
        }
        .error-message {
            font-size: 24px;
            font-weight: bold;
            color: #FF0000;
            margin-bottom: 20px;
        }
        .error-description {
            font-size: 18px;
            color: #333;
            margin-bottom: 20px;
        }
        .error-actions {
            display: flex;
            justify-content: center;
        }
        .error-actions a {
            padding: 10px 20px;
            margin: 0 10px;
            text-decoration: none;
            color: #fff;
            background-color: #666;
            border-radius: 5px;
            font-size: 16px;
        }
        .error-actions a:hover {
            background-color: #FF3333;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-message">{{ $error }}</div>
        <div class="error-description">
            {{ $desc }}
        </div>
        <div class="error-actions">
            <a href="https://www.northerntrust.com/">Home</a>
        </div>
    </div>
</body>
</html>
