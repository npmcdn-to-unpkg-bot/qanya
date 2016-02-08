@if ( Config::get('app.debug') )
    <script type="text/javascript">
        document.write('<script src="//localhost:35729/livereload.js?snipver=1" type="text/javascript"><\/script>')
    </script>
@endif

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1, maximum-scale=1, user-scalable=no" />
    <meta name="csrf-token" content="{!! csrf_token() !!}">


    <title>Qanya</title>

    <!-- Fonts -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css" rel='stylesheet' type='text/css'>
    <link href="https://fonts.googleapis.com/css?family=Lato:100,300,400,700" rel='stylesheet' type='text/css'>

    <!-- Angular Material CSS now available via Google CDN; version 0.9.4 used here -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/angular-material/1.0.5/angular-material.min.css">

    <!-- Styles -->
    {{--<link href="/bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">--}}
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">
    <link href="/assets/css/all.css" rel="stylesheet">
    {{-- <link href="{{ elixir('css/app.css') }}" rel="stylesheet"> --}}
</head>

<body id="app-layout" ng-app="App">
    <nav class="navbar navbar-default purple" style="height: 63px;">
        <div class="">
            <div class="navbar-header">
                <!-- Collapsed Hamburger -->
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                    <span class="sr-only">Toggle Navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>

                <!-- Branding Image -->
                <a class="navbar-brand white-font" href="{{ url('/') }}">
                    Qanya
                </a>
            </div>

            <div class="collapse navbar-collapse container" id="app-navbar-collapse">
                <!-- Left Side Of Navbar -->
                <ul class="nav navbar-nav">
                    <li><a href="{{ url('/home') }}">Home</a></li>
                </ul>

                <!-- Right Side Of Navbar -->
                <ul class="nav navbar-nav navbar-right">
                    <!-- Authentication Links -->
                    @if (Auth::guest())
                        <li><a href="{{ url('/login') }}">Login</a></li>
                        <li><a href="{{ url('/register') }}">Register</a></li>
                    @else
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                {{ Auth::user()->name }} <span class="caret"></span>
                            </a>

                            <ul class="dropdown-menu" role="menu">
                                <li><a href="{{ url('/logout') }}"><i class="fa fa-btn fa-sign-out"></i>Logout</a></li>
                            </ul>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    @yield('content')

    <!-- bower:js -->
    <script src="/bower_components/jquery/dist/jquery.min.js"></script>
    <script src="/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- endbower -->

    <!-- Angular Material Dependencies -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/angular.js/1.5.0/angular.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/angular.js/1.5.0/angular-animate.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/angular.js/1.5.0/angular-aria.min.js"></script>


    {{--ng-flow--}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ng-flow/2.7.1/ng-flow-standalone.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ng-flow/2.7.1/ng-flow.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ng-flow/2.7.1/ng-flow.min.js"></script>


    <!-- Angular Material Javascript now available via Google CDN; version 0.9.4 used here -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/angular-material/1.0.5/angular-material.min.js"></script>


    <script src="/assets/js/ngApps.js"></script>
    <script src="/assets/js/postController.js"></script>
    <script src="/assets/js/home.js"></script>

    <script type="text/javascript">
        $.ajaxSetup({
            headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
        });
    </script>

    {{--
    <!-- JavaScripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>--}}
    {{-- <script src="{{ elixir('js/app.js') }}"></script> --}}
</body>
</html>
