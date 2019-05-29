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
<script src="js/jquery-3.2.1.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/jquery.slicknav.min.js"></script>
<script src="js/owl.carousel.min.js"></script>
<script src="js/jquery.nicescroll.min.js"></script>
<script src="js/jquery.zoom.min.js"></script>
<script src="js/jquery-ui.min.js"></script>
<script src="js/main.js"></script>
@yield('script')
</body>

</html>
