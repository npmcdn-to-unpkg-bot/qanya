@extends('layouts.app')


@section('content')


    <div class="row">

        <div class="col-md-7" style="background:lightblue">
            <div class="card-columns">
                @for($i=0;$i<10;$i++)
                <div class="card">
                    <img class="card-img-top" data-src="..." alt="Card image cap">
                    <div class="card-block">
                        <h4 class="card-title">Card title that wraps to a new line</h4>
                        <p class="card-text">This is a longer card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.</p>
                    </div>
                </div>
                @endfor
            </div>
            @include('html.topic-list',compact('topics'))
        </div>

        <div class="col-md-5 col-xs-12">

            <md-header class="md-headline">
                MOST RECOMMENDED TODAY
            </md-header>

            <md-content>
            <md-card>
                <md-card-title>
                    <md-card-title-text>
                        <span class="md-headline">Card with image</span>
                        <span class="md-subhead">Small</span>
                    </md-card-title-text>
                    <md-card-title-media>
                        <div class="md-media-sm card-media">
                            test
                        </div>
                    </md-card-title-media>
                </md-card-title>
                <md-card-actions layout="row" layout-align="end center">
                    <md-button>Action 1</md-button>
                    <md-button>Action 2</md-button>
                </md-card-actions>
            </md-card>
            </md-content>

            <div class="card">
                <img class="card-img-right" data-src="..." alt="Card image cap">
                <div class="card-block">
                    <h4 class="card-title">Card title that wraps to a new line</h4>
                    <p class="card-text">This is a longer card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.</p>
                </div>
            </div>

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
