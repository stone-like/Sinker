<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SPA</title>
    <link rel="stylesheet" href="{{asset('css/app.css')}}">
    <link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">
</head>
<body>
    <div id="app">
            <app-home></app-home>
    </div>
<script src="{{asset('js/app.js')}}"></script>
</body>
</html>
