@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="container">
        <div class="col-xs-7">

            @include('html.post-create',compact('categories'))

            <div id="homeFeed"></div>

        </div>
        <div class="col-xs-5">
            <div class="media panel md-padding">
                <div class="media-body">
                    {{--{{ Auth::user() }}--}}
                    <h4 class="media-heading">
                        <a href="">
                        {{ Auth::user()->name }}
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
                <li class="nav-item btn-success-outline" role="presentation">
                    <a href="#" onclick="getFeedCate('{{ $cate->slug }}')">{{$cate->name}}</a>
                </li>
            @endforeach
            </ul>
            {{--{{ $categories }}--}}
{{--            {{ Auth::user() }}--}}
        </div>
        {{--<div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>

                <div class="panel-body">
                    You are logged in!
                </div>
            </div>
        </div>--}}
        </div>
    </div>
</div>
@endsection
