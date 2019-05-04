<!DOCTYPE html>
<html lang="en">

<head>

    @include('layout.head')

    @yield('head')

</head>

<body>

<div id="app" class="container-fluid">
    @include('layout.nav')

    @yield('content')

    @include('layout.footer')

    @yield('script')
</div>

</body>

</html>
