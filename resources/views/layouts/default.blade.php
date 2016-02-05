<!doctype html>
<html>
<head>
    @include('includes.head')
    <!-- bower:css -->
    <!-- endbower -->
</head>
<body>
    <div class="container">

        <header class="row">
            @include('includes.header')
        </header>

        <div id="main" class="row">

            @yield('content')

        </div>

        <footer class="row">
            @include('includes.footer')
        </footer>

    </div>
    <!-- bower:js -->
    <!-- endbower -->
</body>
</html>