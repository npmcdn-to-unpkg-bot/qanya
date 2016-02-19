@extends('layouts.app')

@section('content')
    <md-content ng-controller="PostCtrl as postCtrl">
    <div class="container-fluid">
        <div class="layoutSingleColumn">

                <span>
                    <i class="fa fa-clock-o fa-x"></i>{{ $created_at }}
                </span>

                <h1 class="md-display-1">{{ $title }}</h1>
                <p class="reading">
                    {!! nl2br($body) !!}
                </p>

                {{-- Share button --}}
                <div class="fb-share-button"
                     data-href="https://developers.facebook.com/docs/plugins/"
                     data-layout="icon_link"></div>

                <span>
                    <script type="text/javascript" src="//media.line.me/js/line-button.js?v=20140411" ></script>
                    <script type="text/javascript">
                        new media_line_me.LineButton({"pc":false,"lang":"en","type":"a"});
                    </script>
                </span>

            <md-divider></md-divider>


            {{-- Author section --}}
            <div class="media md-margin">
                <div class="media-left">
                    <a href="#">
                        <img class="media-object img-fluid img-circle"
                             width="60px"
                             src="https://avatars3.githubusercontent.com/u/11863395?v=3&s=460"
                             alt="...">
                    </a>
                </div>
                <div class="media-body">
                    <h4 class="media-heading">
                        {{ $username }}
                    </h4>
                    something here
                    <div>
                        <b>10</b> post
                    </div>
                </div>
            </div>

            @if (Auth::user())
                <form ng-submit="postCtrl.postReply('{{$uuid}}')">
                    <div class="media md-margin">
                        <div class="media-left">
                            <a href="#">
                                <img class="media-object img-fluid img-circle"
                                     width="60px"
                                     src="https://avatars3.githubusercontent.com/u/11863395?v=3&s=460"
                                     alt="...">
                            </a>
                        </div>
                        <div class="media-body">
                            <h4 class="media-heading">
                                {{ $username }}
                            </h4>
                            <md-input-container>
                                <label>Title</label>
                                <input
                                        ng-model="postCtrl.topicReply"
                                        name="postTitle"
                                        required autocomplete="off">
                            </md-input-container>
                            <md-button type="submit"
                                       class="md-raised md-primary">Submit</md-button>
                        </div>
                    </div>
                </form>
            @else
                <div class="media md-margin">
                    <div class="media-left">
                        <a href="#">
                            <img class="media-object img-fluid img-circle"
                                 width="36px"
                                 src="https://avatars3.githubusercontent.com/u/11863395?v=3&s=460"
                                 alt="...">
                        </a>
                    </div>
                    <div class="media-body">
                        <h4 class="media-heading">
                            Write a response
                        </h4>
                    </div>
                </div>
            @endif

            <div id="reply-{{$uuid}}">

            </div>

            <md-content>
                <md-list>
                    <md-subheader class="md-no-sticky">Comments </md-subheader>
                    <md-list-item class="md-3-line">
                        <img ng-src="https://avatars3.githubusercontent.com/u/11863395?v=3&s=460"
                             class="md-avatar"
                              />
                        <div class="md-list-item-text" layout="column">
                            <h3> Username </h3>
                            <h4> item.what</h4>
                            <p> item.notes</p>
                        </div>
                    </md-list-item>
                </md-list>
            </md-content>
        </div>
    </div>
    </md-content>
@endsection
