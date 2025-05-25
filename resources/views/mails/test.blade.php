<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Test Email</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            padding: 20px;
        }
        .email-container {
            background: white;
            padding: 20px;
            border-radius: 8px;
            max-width: 600px;
            margin: auto;
        }
        .header {
            background-color: #28a745;
            color: white;
            padding: 10px;
            text-align: center;
            border-radius: 6px 6px 0 0;
        }
        .content {
            margin-top: 20px;
        }
        .footer {
            margin-top: 30px;
            font-size: 12px;
            color: #888;
            text-align: center;
        }
    </style>
</head>
<body>
<div class="email-container">
    <div class="header">
        <h2>Laravel Test Mail</h2>
    </div>
    <div class="content">
        <p>Hello,</p>
        <p>This is a test email sent from your Laravel application.</p>
        <p>If you're seeing this, your mail setup is working correctly!</p>
    </div>
    <div class="footer">
        &copy; {{ date('Y') }} YourApp. All rights reserved.
    </div>
</div>
</body>
</html>
