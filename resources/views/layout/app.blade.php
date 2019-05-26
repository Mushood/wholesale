<!DOCTYPE html>
<html lang="en">

<head>

    @include('layout.head')

    @yield('head')

</head>

<body>
<!-- Page Preloder -->
<div id="preloder">
    <div class="loader"></div>
</div>

<div id="app">
    @include('layout.nav')

    @yield('content')

    @include('layout.footer')


</div>
<script src="{{asset('js/app.js')}}"></script>
@yield('script')
</body>

</html>
