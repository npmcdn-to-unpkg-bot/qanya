@extends('layouts.app')

@section('content')
    <div class="row">
        <section layout="row" ng-controller="PostCtrl as postCtrl">
            <md-content flex layout-padding>
                {{ $title }}

                <div layout="column">

                    @if(Auth::check())
                        @include('html.post-create',compact('categories'))
                    @endif

                    <span class="md-title">
                        @{{ postCtrl.feedName }}
                    </span>

                @if(Auth::user())
                    <a class="btn btn-success-outline pull-right"
                       ng-init="postCtrl.followTagStatus('{{Auth::user()->uuid}}', '{{$title}}')"
                       ng-click="postCtrl.followTag('{{Auth::user()->uuid}}','{{$title}}')">
                        @{{ postCtrl.tagFollowStatus }}
                    </a>
                @endif

                    <div id="homeFeed">
                        @include('html.feed-list',compact('feeds'))
                    </div>
                </div>
            </md-content>

            <md-sidenav class="md-sidenav-right md-component-id="right" md-is-locked-open="$mdMedia('gt-sm')">

                @include('html.profile-badge')
                @include('html.category-nav',compact('categories'))

            </md-sidenav>
        </section>
    </div>
@endsection