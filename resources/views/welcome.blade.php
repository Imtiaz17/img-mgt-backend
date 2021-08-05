<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@200;600&display=swap" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <title>{{env('APP_NAME')}}</title>
</head>
<body>
    <div id="app">
          <v-app>
            <app-dashboard></app-dashboard>
        </v-app>   
    </div>

    <script src="{{ mix('js/app.js') }}"></script>
</body>
</html>