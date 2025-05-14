<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Google Calendar Clone</title>
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Scripts and Styles -->
    @vite(['resources/css/app.css', 'resources/js/main.js'])
</head>
<body>
    <div id="app"></div>
</body>
</html> 