@extends('layouts.app')

@section('content')

<div class="row">
    <md-content ng-controller="PostCtrl as postCtrl" class="md-padding">
        
            <div class="layoutSingleColumn">
                {{$is_user}}
                    <span>
                        <i class="fa fa-clock-o fa-x"></i>{{ $created_at }}
                    </span>

                    <h1 class="md-display-1">{{ $title }}</h1>
                    <p class="reading">
                        {!! nl2br($body) !!}
                        <p>
                            @if($tags)
                                @foreach($tags as $tag)
                                    <a href="/tag/{{$tag}}">#{{$tag}}</a>
                                @endforeach
                            @endif
                        </p>
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
                            <img class="media-object"
                                 width="60px"
                                 src="https://avatars3.githubusercontent.com/u/11863395?v=3&s=460"
                                 alt="...">
                        </a>
                    </div>
                    <div class="media-body">
                        <h4 class="media-heading">
                            <a href="/{{ $username }}">
                                {{ $user_fname }}
                            </a>
                        </h4>
                        {{ $user_descs }}
                        <div>
                            <b>10</b> post
                        </div>
                    </div>

                    <!-- Follow Button -->
                    <div class="media-right">                        
                        @if(is_null($is_user))
                            <button class="btn btn-success-outline"
                                    ng-init="postCtrl.isFollow('{!! $topics_uid !!}')"
                                    ng-click="postCtrl.followUser('{!! $topics_uid !!}')">
                                @{{ postCtrl.postFollow }}
                            </button>
                        @endif
                    </div>
                </div>
        </div>    
    </md-content>
</div>
            <div class="layoutSingleColumn" ng-controller="PostCtrl as postCtrl">

                @if (Auth::user())
                    <form ng-submit="postCtrl.postReply('{{$uuid}}','{{$topics_uid}}')">
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
                                    You
                                </h4>
                                <div contenteditable="true" ng-model="postCtrl.topicReply" id="topicReplyContainer" class="card">
                                write something here
                                </div>                                
                                <md-button type="submit"
                                           class="md-raised md-primary">Submit</md-button>
                            </div>
                        </div>
                    </form>
                @else
                    <div class="media md-margin">
                        <div class="media-left">
                            <a href="#">
                                <img class="media-object"
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
                
                    <md-list id="reply_append_{{$uuid}}"></md-list>

                    @for($i=0;$i<count($topic_replies);$i++)
                     
                    <md-list>
                        <md-list-item class="md-3-line">
                            <img ng-src="https://avatars3.githubusercontent.com/u/11863395?v=3&s=460"
                                 class="md-avatar"/>
                            <div class="md-list-item-text" layout="column">
                                <h3>
                                    <a href="/{{ $topic_replies[$i]->displayname }}" target="_blank">
                                    {{ $topic_replies[$i]->firstname }}
                                    </a>
                                </h3>
                                <h4> {!! $topic_replies[$i]->body !!}</h4>
                                {!! Carbon\Carbon::parse($topic_replies[$i]->replycreated_at)->diffForHumans() !!}
                            </div>
                        </md-list-item>
                    </md-list>

                    @endfor
                
            
            </div>

    <script>

        socket.on("reply_append_{{ $uuid }}:App\\Events\\TopicReplyEvent", function(message){

            console.log(message);


            $.get( "/replyView/", { replyReq: message } )
                    .done(function( data ) {
                        $('#reply_append_{{ $uuid }}').prepend(data);
                        createNotificaiton('New Reply!',
                                'http://www.techigniter.in/wp-content/uploads/2015/07/logo-icon.png',
                                'You have a new reply from your topic!');
                    });
        });

    </script>
@endsection