@extends('layouts.app')

@section('content')

    <div class="row" ng-controller="PostCtrl as postCtrl">

        <md-content class="md-padding">
        <div class="col-md-7">

            <div class="row">

                @if(Auth::user())
                    <a class="btn btn-success-outline pull-right"
                       ng-init="postCtrl.followTagStatus('{{Auth::user()->uuid}}', '{{$title}}')"
                       ng-click="postCtrl.followTag('{{Auth::user()->uuid}}','{{$title}}')">
                        @{{ postCtrl.tagFollowStatus }}
                    </a>
                @endif
                <h1 class="md-display-1 pull-left">
                    {{ $title }}
                </h1>

            </div>

            @if(Auth::check())
                <md-content class="md-padding">
                @include('html.post-create',compact('categories'))
                </md-content>
            @endif

            <span class="md-title">
                @{{ postCtrl.feedName }}
            </span>


            <md-content class="md-padding">
                <div id="homeFeed">
                    @include('html.feed-list',compact('feeds'))
                </div>
            </md-content>
        </div>
        <div class="col-md-5 col-xs-12">

            @include('html.profile-badge')
            @include('html.category-nav',compact('categories'))
        </div>
        </md-content>
    </div>


@endsection