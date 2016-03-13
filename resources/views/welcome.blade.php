@extends('layouts.app')


@section('content')

    <div class="row" >

        <div class="col-md-7">

            @if(Auth::user())
                @include('html.post-create',compact('categories'))
            @endif

            <md-tabs md-dynamic-height md-border-bottom>
                <md-tab label="Latest feed">
                    <md-content class="md-padding">
                        <div id="homeFeed">
                            @include('html.feed-list',compact('topics'))
                        </div>
                    </md-content>
                </md-tab>
                <md-tab label="Most recomended">
                    <md-content class="md-padding">
                        <div id="homeFeed">
                            @include('html.feed-list',compact('topics'))
                        </div>
                    </md-content>
                </md-tab>
                {{-- IF user login --}}
                @if(Auth::user())
                {{--<div ng-controller="ProfileCtrl as profileCtrl">--}}
                    <md-tab label="bookmark" ng-click="profileCtrl.getUserBookmark('{{Auth::user()->uuid}}')">
                        <md-content class="md-padding">
                            <div id="userBookmark">
                                @{{profileCtrl.userBookmark}}
                            </div>
                        </md-content>
                    </md-tab>

                    <md-tab label="history" ng-click="profileCtrl.getUserHistory('{{Auth::user()->uuid}}')">
                        <md-content class="md-padding">
                            <div id="userViewHistory">
                                @{{ profileCtrl.userHistory }}
                            </div>
                        </md-content>
                    </md-tab>
                {{--</div>--}}
                @endif
            </md-tabs>


        </div>

        <div class="col-md-5 col-xs-12" ng-controller="PostCtrl as postCtrl">
            @if(Auth::user())
                <div class="media panel md-padding" ng-init="profileCtrl.getUserStat('{{Auth::user()->uuid}}')">
                    <div class="media-body">
                        <h4 class="media-heading">
                            <a href="/{!! Auth::user()->displayname !!}">
                                {{ Auth::user()->firstname }}
                            </a>
                        </h4>
                        {{ Auth::user()->description }}
                        <div ng-controlloer="ProfileCtrl as profileCtrl">
                            <b> {{ Auth::user()->posts }}</b> posts
                            <b> @{{ profileCtrl.userFollower }}</b> followers
                            <b> @{{ profileCtrl.userUpvoted }}</b> upvote
                        </div>
                    </div>
                    <div class="media-right">
                        <a href="#">
                            <img class="media-object"
                                 width="80px"
                                 src="{{ Auth::user()->profile_img }}"
                                 alt="...">
                        </a>
                    </div>
                </div>

                <div class="media panel md-padding">
                    <div ng-init="postCtrl.userTagList('{{Auth::user()->uuid}}')"
                            id="userTagList">
                    </div>
                </div>

            @endif

            <ul class="nav nav-pills">
                @foreach ($categories as $cate)
                    <li class="nav-item btn-success-outline"
                        role="presentation">
                        <a href="/channel/{{ $cate->slug }}" class="btn btn-success-outline md-margin"
                           ng-click="postCtrl.getFeedCate('{{ $cate->slug }}','{{$cate->name}}');
                                    postCtrl.feedFollowStatus('{{ $cate->slug }}')">
                            {{$cate->name}}</a>
                    </li>
                @endforeach
            </ul>

         {{--   <md-header class="md-headline">
                MOST RECOMMENDED TODAY
            </md-header>

                @for($i=0;$i<10;$i++)
                    <div class="media">
                        <div class="media-body">
                            <h4 class="media-heading">Media heading</h4>
                            ...
                        </div>
                        <div class="media-right">
                            <a href="#">
                                <img class="media-object"
                                     src="https://www.eliteflightpros.com/wp-content/uploads/2014/07/bangkok.jpg"
                                     style="max-height: 100px;"
                                     alt="...">
                            </a>
                        </div>
                    </div>
                    --}}{{--<md-list-item class="md-3-line md-long-text">
                        <img ng-src="https://avatars2.githubusercontent.com/u/11863395?v=3&u=5ea5a91b3fd012a3e232ff41faff0107c07f9429&s=140"
                                 class="md-avatar"
                                 alt="@{{todos[0].who}}" />
                        <div class="md-list-item-text">
                            <h3>username here</h3>
                            <p>
                                Secondary line text Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam massa quam.
                            </p>
                        </div>
                    </md-list-item>
                    <img src="https://www.eliteflightpros.com/wp-content/uploads/2014/07/bangkok.jpg"
                         class="img-fluid">
                    <md-divider></md-divider>--}}{{--
                @endfor--}}
        </div>
    </div>


@endsection
