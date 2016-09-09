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



    {{-- Toastr -> https://github.com/Foxandxss/angular-toastr --}}
    <script src="https://unpkg.com/angular-toastr/dist/angular-toastr.tpls.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/angular-toastr/dist/angular-toastr.css" />


    {{-- BOWER --}}
    <script src="/bower_components/angular-material/angular-material.min.js"></script>
    <script src="/bower_components/angular-cookies/angular-cookies.min.js"></script>
    <script src="/bower_components/angular-sanitize/angular-sanitize.min.js"></script>
    <script src="/bower_components/angular-translate/angular-translate.min.js"></script>
    <script src="/bower_components/angular-messages/angular-messages.js"></script>

    <script src="/js/all.js"></script>

</head>

<body id="app-layout" ng-app="App" ng-controller="ProfileCtrl as profileCtrl" ng-cloak layout='row'>


    <div id="fb-root"></div>
    <script>(function(d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s); js.id = id;
        js.src = "//connect.facebook.net/en_GB/sdk.js#xfbml=1&version=v2.5&appId=182388651773669";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));</script>


    <div layout="column" flex>

        <md-toolbar ng-controller="PostCtrl as postCtrl" style="box-shadow: 1px 2px 8px rgba(0, 0, 0, .5);">

            <div class="md-toolbar-tools">

                {{-- show/hide button when in mobile--}}
                <md-button
                        hide-gt-xs
                        aria-label="menu"
                        ng-click="profileCtrl.toggleMobile();"
                        class="md-icon-button"
                        class="md-icon-button">
                    <md-icon md-menu-origin md-svg-icon="/assets/icons/ic_menu_white_24px.svg"></md-icon>
                </md-button>


                <a href="/">
                    <h2>
                        <span>QANYA</span>
                    </h2>
                </a>

                <span flex></span>

                {{-- Languages--}}
                <lang-select  hide-xs></lang-select>

                {{-- Profile and Login --}}
                @if (Auth::guest())
                    <md-button hide-xs aria-label="Login" ng-href="{{ url('/login') }}">
                        @{{ 'KEY_LOGIN_REGISTER' | translate }}
                    </md-button>
                @else

                    @if(Auth::user()->confirmed == 1)

                        {{-- Notification--}}
                       {{-- <md-button
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
                        </md-button>--}}

                        {{-- Profile button --}}
                        <md-button
                                hide-xs
                                aria-label="{!! Auth::user()->displayname !!}"
                                href="/{!! Auth::user()->displayname !!}">
                                {!! Auth::user()->firstname !!}
                                <img ng-src="{!! Auth::user()->profile_img !!}" class="img-circle" width="27px">
                        </md-button>

                    @endif
                @endif
            </div>
        </md-toolbar>

        {{-- Main container--}}
        <md-content layout-align="center">

            @yield('content')

        </md-content>

    </div>


    {{-- Profile toggle for mobile --}}
    <md-sidenav class="md-sidenav-left md-whiteframe-4dp" md-component-id="mobile">
        <md-content>
            @if(Auth::user())
                {{-- Profile badge--}}
                @include('html.profile-badge')

                {{-- User tags--}}
                <div class="md-padding">
                    <user-tags ng-init="postCtrl.userTagList('{{Auth::user()->uuid}}')"
                               data="postCtrl.userTags"></user-tags>
                </div>

                {{-- Notification--}}
                <div class="md-padding">
                    <md-button
                            class="md-fab md-mini md-primary"
                            aria-label="notification"
                            ng-click="profileCtrl.ackNotificataion();
                                          profileCtrl.toggleRight();
                                          profileCtrl.listNotification()">

                        <md-icon md-menu-origin
                                 md-svg-icon="/assets/icons/ic_notifications_white_24px.svg"
                                 style="color: greenyellow;"></md-icon>

                            <span id="notification_{{Auth::user()->uuid}}"
                                  ng-init="profileCtrl.userNotification()">
                                @{{ profileCtrl.unreadNotification }}
                            </span>
                    </md-button>
                </div>

                {{-- Log out button --}}
                <div class="">
                    <md-button class="md-primary" ng-href="{{ url('/logout') }}">
                        @{{ 'KEY_LOGOUT' | translate }}
                    </md-button>
                </div>
            @else
                <div class="md-padding">
                    <md-button aria-label="Login" ng-href="{{ url('/login') }}">
                        @{{ 'KEY_LOGIN_REGISTER' | translate }}
                    </md-button>
                </div>
            @endif

            {{-- Languages--}}
            <div class="md-padding">
                <lang-select></lang-select>
            </div>

        </md-content>
    </md-sidenav>



    {{-- Sidebar notification --}}
    @if(Auth::user())
        <md-sidenav class="md-sidenav-right md-whiteframe-z2"
                    md-component-id="alertSideNav">
            <md-toolbar class="md-theme-dark">
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



    <script type="text/javascript">
        $.ajaxSetup({
            headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
        });
        <?php
       if(empty(Auth::user()->current_city)):?>

            $.getJSON('http://ipinfo.io', function(data){
                console.log(data)
                $.post( "/api/updateUserGeo/",
                        {   geo_city:       data.city,
                            geo_country:    data.country
                        }
                )})

        <?php
        endif;
        ?>

    </script>

</body>
</html>
