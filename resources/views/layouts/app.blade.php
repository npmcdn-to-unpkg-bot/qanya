@if ( Config::get('app.debug') )
    <script type="text/javascript">
        document.write('<script src="//192.168.0.100:35729/livereload.js?snipver=1" type="text/javascript"><\/script>')
    </script>
@endif

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1, maximum-scale=1, user-scalable=no" />
    <meta name="csrf-token" content="{!! csrf_token() !!}">

    {{-- SEO STUFF --}}
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}

    <!-- Fonts -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css" rel='stylesheet' type='text/css'>
    <link href="https://fonts.googleapis.com/css?family=Lato:100,300,400,700" rel='stylesheet' type='text/css'>

    <!-- Angular Material CSS now available via Google CDN; version 0.9.4 used here -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/angular-material/1.0.5/angular-material.min.css">

    <!-- Styles -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="/assets/css/all.css" rel="stylesheet">
    {{-- <link href="{{ elixir('css/app.css') }}" rel="stylesheet"> --}}

    <script>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
                    (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
                m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

        ga('create', 'UA-73596733-1', 'auto');
        ga('send', 'pageview');
    </script>

    {{--Socket.io--}}
    {!! HTML::script('https://cdn.socket.io/socket.io-1.4.5.js') !!}

    <script>

        /*
        *
        * Desktop notification
        *
        * */
        document.addEventListener('DOMContentLoaded', function () {
            //check if the browser supports notifications
            if (!("Notification" in window)) {
                return;
            }
            if (Notification.permission !== "granted"){
                //if permission is not granted then ask user for permission
                Notification.requestPermission();
            }
        });

        function createNotificaiton(theTitle, theIcon, theBody){
            var options = {
                icon: theIcon,
                body: theBody,
            };
            var notification = new Notification(theTitle, options);
            //Disappear in 5 secs
            setTimeout(notification.close.bind(notification), 5000);
        }
        /*End Desktop notification*/



        /*
         * ---------------------------------------
         *
         * SOCKETS
         *
         * --------------------------------------
         */
        var socket = io('http://192.168.0.100:3000');
        socket.on("test-channel:App\\Events\\EventName", function(message){
            // increase the power everytime we load test route
            $('#power').text(parseInt($('#power').text()) + parseInt(message.data.power));
        });

        @if(Auth::user())

            socket.on("reply_to_{{Auth::user()->uuid}}:App\\Events\\TopicReplyEvent", function(message){
            $('#notification_{!!  Auth::user()->uuid !!}').text(message.count);
            });

            socket.on("notification_{{Auth::user()->uuid}}:App\\Events\\FollowUserEvent", function(message){
                createNotificaiton('New Follower!',
                                    'http://www.techigniter.in/wp-content/uploads/2015/07/logo-icon.png',
                                    'You have a new follower!');
                $('#notification_{!!  Auth::user()->uuid !!}').text(message.count);

            });

        @endif

    </script>
</head>

<body id="app-layout" ng-app="App" ng-controller="ProfileCtrl as profileCtrl" ng-cloak>

    <div id="fb-root"></div>
    <script>(function(d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s); js.id = id;
        js.src = "//connect.facebook.net/en_GB/sdk.js#xfbml=1&version=v2.5&appId=182388651773669";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));</script>

    <div class="pos-f-t">
    <md-toolbar>
        <div class="md-toolbar-tools">
            <h2>
                <span>
                    <a class="navbar-brand white-font" href="{{ url('/') }}">
                        Qanya
                    </a>
                </span>
            </h2>
            
            @if (!Auth::guest())
                <a class="nav-link" href="{{ url('/home') }}">
                    Home
                </a>
            @endif
            <span flex></span>
            @if (Auth::guest())
                <md-button aria-label="Login" ng-href="{{ url('/login') }}">
                    Login
                </md-button>
                <md-button aria-label="Join us" ng-href="{{ url('/register') }}">
                    join us
                </md-button>
            @else

                <md-button ng-click="profileCtrl.toggleRight();
                                    profileCtrl.listNotification()"
                           class="md-primary">
                    Toggle right
                </md-button>
                <md-button
                        aria-label="notification"
                        ng-click="profileCtrl.ackNotificataion();
                                  profileCtrl.toggleRight()
                                  ">
                    <i class="fa fa-bell-o fa-x"></i>
                    <span id="notification_{{Auth::user()->uuid}}"
                          ng-init="profileCtrl.userNotification()">
                        @{{ profileCtrl.unreadNotification }}
                    </span>
                </md-button>

                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                        {{ Auth::user()->firstname }} <span class="caret"></span>
                    </a>

                    <ul class="dropdown-menu" role="menu">
                        <li><a href="{{ url('/logout') }}"><i class="fa fa-btn fa-sign-out"></i>Logout</a></li>
                    </ul>
                </li>
            @endif

        </div>
    </md-toolbar>
    </div>


    <div class="container">
        @yield('content')

        {{-- Sidebar notification --}}
        <md-sidenav class="md-sidenav-right md-whiteframe-z2"
                    md-component-id="alertSideNav">
            <md-toolbar class="md-theme-light">
                <h1 class="md-toolbar-tools">Sidenav Right</h1>
            </md-toolbar>
            <md-content>
                <md-list>
                    <md-subheader class="md-no-sticky">Notification</md-subheader>
                    <md-list-item class="md-3-line" ng-repeat="notification in profileCtrl.notificationList">
                        <img ng-src="@{{item.face}}?@{{$index}}" class="md-avatar" alt="@{{item.who}}" />
                        <div class="md-list-item-text" layout="column">
                            <p>
                                @{{ notification.firstname }}
                                @{{ notification.body }}
                                to post
                                @{{ notification.topic }}
                            </p>
                            <span am-time-ago="@{{ notification.created_at }} | amParse:'YYYY-MM-DD HH:mm:ss"></span>
                        </div>
                    </md-list-item>
                </md-list>
            </md-content>
        </md-sidenav>
    </div>






    <!-- bower:js -->
    <script src="/bower_components/jquery/dist/jquery.min.js"></script>
    {{--<script src="/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>--}}
    <!-- endbower -->

{{--    {!! Html::style('https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css') !!}--}}
    {!! Html::script('https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.2/js/bootstrap.min.js') !!}

    <!-- Angular Material Dependencies -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/angular.js/1.5.0/angular.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/angular.js/1.5.0/angular-animate.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/angular.js/1.5.0/angular-aria.min.js"></script>

    <!-- load momentJS (required for angular-moment) -->
    <script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment.min.js"></script>

    <!-- load angular-moment -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/angular-moment/1.0.0-beta.4/angular-moment.min.js"></script>


    {{--ng-flow--}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ng-flow/2.7.1/ng-flow-standalone.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ng-flow/2.7.1/ng-flow.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ng-flow/2.7.1/ng-flow.min.js"></script>


    <!-- Angular Material Javascript now available via Google CDN; version 0.9.4 used here -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/angular-material/1.0.5/angular-material.min.js"></script>
    <script src="/js/all.js"></script>

    <script type="text/javascript">
        $.ajaxSetup({
            headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
        });
        <?php
       if(empty(Auth::user()->current_city)):?>
           {{--Local storage --}}
           if(typeof(Storage) !== "undefined") {
            $.getJSON('http://ipinfo.io', function(data){
                console.log(data)
                $.post( "/api/updateUserGeo/",
                        {   geo_city:       data.city,
                            geo_country:    data.country
                        }
                )})
            } else {
                // Sorry! No Web Storage support..
            }
        <?php
        endif;
        ?>
    </script>

    {{--
    <!-- JavaScripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>--}}
    {{-- <script src="{{ elixir('js/app.js') }}"></script> --}}




</body>
</html>
