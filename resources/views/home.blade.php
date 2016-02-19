@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row" ng-controller="PostCtrl as postCtrl">
        <div class="container">

            <p id="power">0</p>

            <div class="col-xs-7">

                @include('html.post-create',compact('categories'))

                @{{ postCtrl.slug }}
                <a class="btn btn-link"
                   ng-if="postCtrl.slug"
                   ng-click="postCtrl.followCate(postCtrl.slug)">
                    follow+
                </a>

                <div id="homeFeed">
                    @include('html.feed-list',compact('feeds'));
                </div>

            </div>
            <div class="col-xs-5">
                <div class="media panel md-padding">
                    <div class="media-body">
                        {{--{{ Auth::user() }}--}}
                        <h4 class="media-heading">
                            <a href="/{!! Auth::user()->displayname !!}">
                            {{ Auth::user()->firstname }}
                            </a>
                        </h4>
                        something here
                        <div>
                            <b>10</b> post
                        </div>
                    </div>
                    <div class="media-right">
                        <a href="#">
                            <img class="media-object img-fluid"
                                 width="80px"
                                 src="https://avatars3.githubusercontent.com/u/11863395?v=3&s=460"
                                 alt="...">
                        </a>
                    </div>
                </div>
                <ul class="nav nav-pills">
                    @foreach ($categories as $cate)
                        <li class="nav-item btn-success-outline"
                            role="presentation">
                            <a href="#" ng-click="postCtrl.getFeedCate('{{ $cate->slug }}')">{{$cate->name}}</a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
