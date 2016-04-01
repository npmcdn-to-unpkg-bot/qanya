@if ( Config::get('app.debug') )
    <script type="text/javascript">
        document.write('<script src="//<?php echo getenv('SERVER_ADDRESS')?>:35729/livereload.js?snipver=1" type="text/javascript"><\/script>')
    </script>
@endif

<!--

QANYA

ถ้าคุณอ่านตรงนี้อยู่ เราเชื่อว่าคุณคงชอบอ่าน โคดเหมือนกัน มาคุยกันก่อนได้นะครับ
สนใจมาทำงานร่วนกันติดต่อ kantatorn.tardthong@gmail.com นะครับ :)

-->
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
    <link rel="stylesheet" href="/bower_components/angular-material/angular-material.min.css">

    <!-- Styles -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tether/1.2.0/css/tether.min.css" rel="stylesheet">


    <link href="/assets/css/all.css" rel="stylesheet">
    <link href="/assets/css/animate.css" rel="stylesheet">
    {{-- <link href="{{ elixir('css/app.css') }}" rel="stylesheet"> --}}

    <script>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
                    (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
                m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

        ga('create', 'UA-73596733-1', 'auto');
        ga('send', 'pageview');
    </script>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.1/jquery.min.js"></script>

    <!-- Angular Material Dependencies -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/angular.js/1.5.0/angular.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/angular.js/1.5.0/angular-animate.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/angular.js/1.5.0/angular-aria.min.js"></script>

    <!-- Firebase & AngularFire-->
    <script src="https://cdn.firebase.com/js/client/2.2.4/firebase.js"></script><!--  -->
    <script src="https://cdn.firebase.com/libs/angularfire/1.1.3/angularfire.min.js"></script>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.2.0/js/tether.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.2/js/bootstrap.min.js"></script>

    <!-- load momentJS (required for angular-moment) -->
    <script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/angular-moment/1.0.0-beta.4/angular-moment.min.js"></script>


    {{--ng-flow--}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ng-flow/2.7.1/ng-flow-standalone.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ng-flow/2.7.1/ng-flow.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ng-flow/2.7.1/ng-flow.min.js"></script>

    {{--https://github.com/a8m/angular-filter--}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/angular-filter/0.5.8/angular-filter.js"></script>

    {{--Socket.io--}}
    <script src="https://cdn.socket.io/socket.io-1.4.5.js"></script>



    {{-- Toastr -> https://github.com/Foxandxss/angular-toastr --}}
    <script src="https://npmcdn.com/angular-toastr/dist/angular-toastr.tpls.js"></script>
    <link rel="stylesheet" href="https://npmcdn.com/angular-toastr/dist/angular-toastr.css" />


    {{-- BOWER --}}
    <script src="/bower_components/angular-material/angular-material.min.js"></script>
    <script src="/bower_components/angular-cookies/angular-cookies.min.js"></script>
    <script src="/bower_components/angular-sanitize/angular-sanitize.min.js"></script>
    <script src="/bower_components/angular-translate/angular-translate.min.js"></script>

    <script>

        /*
        * Desktop notification
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
         * SOCKETS
         */
        var socket = io('http://<?php echo getenv('SERVER_ADDRESS')?>:3000');
        socket.on("test-channel:App\\Events\\EventName", function(message){
            // increase the power everytime we load test route
            $('#power').text(parseInt($('#power').text()) + parseInt(message.data.power));
        });

        @if(Auth::check())

            socket.on("reply_to_{{Auth::user()->uuid}}:App\\Events\\TopicReplyEvent", function(message){
                createNotificaiton('New Reply!',
                    'http://www.techigniter.in/wp-content/uploads/2015/07/logo-icon.png',
                    'You have a new reply from your topic!');
                $('#notification_{!!  Auth::user()->uuid !!}').text(message.count);
            });

            socket.on("notification_{{Auth::user()->uuid}}:App\\Events\\FollowUserEvent", function(message){
                createNotificaiton('New Follower!',
                                    'http://www.techigniter.in/wp-content/uploads/2015/07/logo-icon.png',
                                    'You have a new follower!');
                $('#notification_{!!  Auth::user()->uuid !!}').text(message.count);

            });

            socket.on("topic_upv_{{Auth::user()->uuid}}:App\\Events\\TopicUpvote", function(message){
                if(message.is_upvote == true)
                {
                    createNotificaiton('Upvote!',
                            'http://www.techigniter.in/wp-content/uploads/2015/07/logo-icon.png',
                            'You have a new Upvote!');
                }
                console.log(message);
                $('#notification_{!!  Auth::user()->uuid !!}').text(message.count);

            });

        @endif

    </script>

    <script src="/js/all.js"></script>

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





    <md-toolbar ng-controller="PostCtrl as postCtrl">

        {{-- Collapsable mobile content --}}
        <div class="collapse" id="navbar-header">
            <div class="container-fluid bg-inverse p-a-1">
                @if (Auth::guest())
                    <md-button aria-label="Login" ng-href="{{ url('/login') }}">
                        @{{ 'KEY_LOGIN_REGISTER' | translate }}
                    </md-button>
                @else
                    {{-- Profile badge--}}
                    @include('html.profile-badge')

                    {{-- User tags--}}
                    <user-tags data="postCtrl.userTags"></user-tags>
                @endif

            </div>
        </div>

        <div class="md-toolbar-tools">

            <md-button
                    aria-label="menu"
                    hide-gt-xs class="md-icon-button" type="button" data-toggle="collapse" data-target="#navbar-header">
                <md-icon md-menu-origin md-svg-icon="/assets/icons/ic_menu_white_24px.svg"></md-icon>
            </md-button>


            <a href="/">
                <h2>
                    <span>QANYA</span>
                </h2>
            </a>

            <span flex></span>

            {{-- Languages--}}
            <md-menu>
                <md-button aria-label="Languages"
                           ng-click="postCtrl.openMenu($mdOpenMenu, $event)">
                    <md-icon md-menu-origin md-svg-icon="/assets/icons/ic_language_white_24px.svg"></md-icon>
                    @{{ profileCtrl.userLang }}
                </md-button>
                <md-menu-content width="4">
                    <md-menu-item>
                        <md-button
                                aria-label="Thai"
                                ng-click="profileCtrl.toggleLang('ไทย')">
                            ไทย
                        </md-button>
                    </md-menu-item>
                    <md-menu-item>
                        <md-button
                                aria-label="English"
                                ng-click="profileCtrl.toggleLang('Eng')">
                            Eng
                        </md-button>
                    </md-menu-item>
                    <md-menu-divider></md-menu-divider>
                </md-menu-content>
            </md-menu>

            {{-- Profile and Login --}}
            @if (Auth::guest())
                <md-button hide-xs aria-label="Login" ng-href="{{ url('/login') }}">
                    @{{ 'KEY_LOGIN_REGISTER' | translate }}
                </md-button>
            @else
                {{-- Notification--}}
                <md-button
                        hide-xs
                        aria-label="notification"
                        ng-click="profileCtrl.ackNotificataion();
                                      profileCtrl.toggleRight();
                                      profileCtrl.listNotification()">

                        <md-icon md-menu-origin md-svg-icon="/assets/icons/ic_notifications_white_24px.svg"></md-icon>

                        <span id="notification_{{Auth::user()->uuid}}"
                              ng-init="profileCtrl.userNotification()">
                            @{{ profileCtrl.unreadNotification }}
                        </span>
                </md-button>

                <md-button aria-label="{!! Auth::user()->displayname !!}"
                           hide-xs href="/{!! Auth::user()->displayname !!}">
                    {!! Auth::user()->firstname !!}
                    <img ng-src="{!! Auth::user()->profile_img !!}" class="img-circle" width="27px">
                </md-button>
            @endif
        </div>
    </md-toolbar>

    {{-- Main container--}}
    <section layout="row" flex>
        <md-content flex>
            <div class="container">
                @yield('content')
            </div>
        </md-content>

        <md-sidenav class="md-sidenav-right md-whiteframe-4dp" md-component-id="mobile">
            <md-toolbar class="md-theme-light">
                <h1 class="md-toolbar-tools">Sidenav Right</h1>
            </md-toolbar>
            <md-content>
                @include('html.profile-badge')

                @if(Auth::user())
                    <user-tags ng-init="postCtrl.userTagList('{{Auth::user()->uuid}}')"
                               data="postCtrl.userTags"></user-tags>
                @endif

            </md-content>
        </md-sidenav>


        {{-- Sidebar notification --}}
        @if(Auth::user())
            <md-sidenav class="md-sidenav-right md-whiteframe-z2"
                        md-component-id="alertSideNav">
                <md-toolbar class="md-theme-light">
                    <h1 class="md-toolbar-tools">
                        <md-icon md-menu-origin md-svg-icon="/assets/icons/ic_notifications_white_24px.svg"></md-icon>
                        @{{ 'KEY_NOTIFICATION' | translate }}</h1>
                </md-toolbar>
                <md-content>
                    <md-list>
                        <md-list-item class="md-3-line" ng-repeat="notification in profileCtrl.notificationList">
                            <img ng-src="@{{item.face}}?@{{$index}}" class="md-avatar" alt="@{{item.who}}" />
                            <div class="md-list-item-text" layout="column">
                                <p>
                                    <a href="/@{{ notification.displayname }}">
                                        @{{ notification.firstname }}
                                    </a>
                                    @{{ notification.body }}
                                    {{Auth::user()->displayName}}
                                    <a href="/{{Auth::user()->displayName}}/@{{ notification.slug }}"
                                    @{{ notification.topic | htmlToPlaintext }}
                                </p>
                                {{--<span am-time-ago="{{ notification.created_at  | amParse:'YYYY-MM-DD HH:mm:ss}}"></span>--}}
                            </div>
                        </md-list-item>
                    </md-list>
                </md-content>
            </md-sidenav>
        @endif
    </section>


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

        $(document).ready(function(){

            $("#topicContent").find( "img" ).each(function(){
                var t = $(this);
                var src = t.attr('src');
                t.attr('class','img-fluid');
            });

        });
    </script>

</body>
</html>
