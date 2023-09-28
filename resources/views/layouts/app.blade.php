<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1, minimum-scale=1, user-scalable=0" />
    
    <title>Faria</title>
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.ico') }}" sizes="32x32" />
    <link href="{{ asset('css/bootstrap.min.css')}}" rel="stylesheet" type="text/css">
    <link href="{{ asset('css/fontawesome.min.css')}}" rel="stylesheet" type="text/css">
    <link href="{{ asset('css/style.css')}}" rel="stylesheet" type="text/css">
    
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.bundle.js') }}"></script>
    <script src="{{ asset('js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('js/moment.min.js') }}"></script>
    <script src="{{ asset('js/script.js') }}"></script>
    <script src="{{ asset('js/lightgallery.min.js') }}"></script>
    <link href="{{ asset('logincss/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('logincss/css/fontawesome.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('logincss/css/style.css') }}" rel="stylesheet" type="text/css">
</head>
<body>
    <div id="app">
        @yield('content')
    </div>
    @yield('custom_js')
    @include('toast')
    @include('success-error-message')
    {!! Toastr::message() !!}
</body>
</html>
