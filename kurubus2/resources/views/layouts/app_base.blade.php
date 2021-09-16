@yield('submit')
<!doctype html>
 <html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
 <head>
     <meta charset="utf-8">
     <meta name="viewport" content="width=device-width, initial-scale=1">
 
     {{-- CSRF Token --}}
     <meta name="csrf-token" content="{{ csrf_token() }}">
 
     <title>{{ config('app.name', 'Timetable Searcher') }}</title>
 
     {{-- Styles --}}
     <link href="{{ mix('/css/app.css') }}" rel="stylesheet">
 </head>
 <body>
 <div class="container">
    <nav class="navbar navbar-light">
        <span class="navbar navbar-brand mx-auto"><a href="http://localhost/timetable"><img src="images/kurubus-logo.png" alt="くるバス"></a></span>
    </nav>
@yield('content')
    <div class="footer text-center">
        2021 Copyright &copy; Pherkad Gamma
    </div>
 </div>
{{-- Scripts --}}
 <script src="{{ mix('/js/app.js') }}" defer></script>
 </body>
 </html>