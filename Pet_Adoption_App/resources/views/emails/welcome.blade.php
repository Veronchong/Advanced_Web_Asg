<!DOCTYPE html>
<html>
<head>
    <title>Welcome to Pet Adoption System</title>
</head>
<body>
    <h1>Welcome to Pet Adoption System, {{ $user->name }}!</h1>
    <p>Thank you for registering with our pet adoption platform.</p>
    <p>You can now browse available pets and submit adoption applications.</p>
    <p>Start your journey by visiting our website: <a href="{{ url('/') }}">Pet Adoption System</a></p>
    <p>If you have any questions, feel free to contact our support team.</p>
    <p>Best regards,<br>The Pet Adoption Team</p>
</body>
</html>