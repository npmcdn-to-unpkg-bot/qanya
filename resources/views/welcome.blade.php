@extends('layouts.app')


@section('content')


    <div class="row">

        <div class="col-md-7">

            <div class="btn-group hidden-md-up" data-toggle="buttons">
                <label class="btn btn-success-outline active">
                    <input type="radio" name="options" id="option1" autocomplete="off" checked>
                    Most Recommended
                </label>
                <label class="btn btn-success-outline">
                    <input type="radio" name="options" id="option2" autocomplete="off">
                    Most Recents
                </label>
                <label class="btn btn-success-outline">
                    <input type="radio" name="options" id="option3" autocomplete="off"> Radio 3
                </label>
            </div>

            {{--<div class="card-columns">--}}
            <div id="homeFeed">
                @include('html.feed-list',compact('topics'))
            </div>
            {{--</div>--}}

        </div>

        <div class="col-md-5 col-xs-12">

            <ul class="nav nav-pills">
                @foreach ($categories as $cate)
                    <li class="nav-item btn-success-outline"
                        role="presentation">
                        <a href="#" class="btn btn-success-outline"
                           ng-click="postCtrl.getFeedCate('{{ $cate->slug }}','{{$cate->name}}');
                                    postCtrl.feedFollowStatus('{{ $cate->slug }}')">
                            {{$cate->name}}</a>
                    </li>
                @endforeach
            </ul>

            <md-header class="md-headline">
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
                    {{--<md-list-item class="md-3-line md-long-text">
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
                    <md-divider></md-divider>--}}
                @endfor
        </div>
    </div>


@endsection
