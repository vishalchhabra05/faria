<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8" />
    <script src="https://cdn.ckeditor.com/4.12.1/standard/ckeditor.js"></script>
    @include('admin::pages.head')
    <!-- Styles -->
    @include('admin::pages.style')
    @include('admin::pages.script')
    @yield('admin::custom_css')
    
    <!-- End Styles -->
</head>
<body>
    @include('admin::pages.header')
    @include('admin::pages.side_bar')
    @yield('admin::content')
    <input type="hidden" value="commit_virendra17-12-2021:2:12">
    @include('admin::pages.footer')
    <!-- JavaScript -->
    
    @yield('admin::script')
    @yield('admin::custom_js')
    @include('toast')
    @include('success-error-message')
    {!! Toastr::message() !!}
    <!-- End Javascript -->
</body>
</html>
