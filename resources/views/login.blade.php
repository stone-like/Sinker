<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>login</title>
    <link rel="stylesheet" href="{{asset('css/app.css')}}">
</head>
<body>
    <div id="app">
           <v-app style="background-image: linear-gradient(to right, #0acffe 0%, #495aff 100%); width:100vw; height:100vh;">
               <app-log></app-log>
           </v-app>
    </div>
<script src="{{asset('js/app.js')}}"></script>
</body>
</html>
