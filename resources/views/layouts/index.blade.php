<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Pxel | Developed by Zisko</title>
        <meta charset="utf-8">
        <meta name="keywords" content="palabra1, palabra2">
        <meta name="description" content="descripcion de la pagina">
        <meta name="author" content="nombre">
        <meta name="copyright" content="copyright 2021">
        <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">

        <meta name="csrf-token" content="{{ csrf_token() }}">
        
        <link rel="icon" href="{{ url('assets/images/favicon.ico') }}" type="image/x-icon">
        <link rel="preconnect" href="https://fonts.gstatic.com">
        <link rel="stylesheet" href="{{ url('assets/css/normalize.css') }}">
        <link rel="stylesheet" href="{{ url('assets/css/style.css') }}">
    </head>
    <body>
        @yield('content')

        <!-- All scripts -->
        <script src="{{ url('assets/js/jquery-3.5.1.min.js') }}"></script>

        @yield('scripts')
    </body>
</html>