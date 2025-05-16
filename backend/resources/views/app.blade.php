<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Google Calendar Clone - Quản lý lịch và sự kiện của bạn">
    <meta name="theme-color" content="#4285F4">
    
    <title>Lịch Google</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="https://calendar.google.com/googlecalendar/images/favicons_2020q4/calendar_31.ico">
    
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Material Symbols Outlined -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Google+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    
    <!-- Scripts and Styles -->
    @vite(['resources/css/app.css', 'resources/js/main.js'])
</head>
<body class="font-google-sans">
    <div id="app"></div>
</body>
</html> 