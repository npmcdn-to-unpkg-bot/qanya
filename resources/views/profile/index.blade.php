@extends('layouts.app')

@section('content')

    <div class="row" ng-controller="ProfileCtrl as profileCtrl">
        <div class="container-fluid">
            <div class="layoutSingleColumn">
                <div class="row">
                    <div class="col-xs-4">
                        <img src="{{ $user->profile_img}}"
                             id="profilePhoto"
                             class="img-fluid img-circle"
                             width="150px">

                            @if($is_user == 'TRUE')
                                <div flow-init
                                     flow-name="uploader.flow"
                                     flow-files-added="profileCtrl.profileImage($files)">
                                    <md-button flow-btn type="file" name="image">
                                        Upload photo
                                    </md-button>
                                </div>
                            @endif
                    </div>

                    <div class="col-xs-8">
                        <h2 class="lead">
                            <strong>
                            {{ $user->firstname }}
                            {{ $user->lastname }}
                            </strong>
                            <p>
                                <small>
                                    {{ $user->displayname }}
                                </small>
                            </p>
                        </h2>
                        <div contenteditable="{{ $is_user }}"
                             class="md-subhead"
                             id= "profileDescription"
                             ng-blur    =   "profileCtrl.updateDescription()"
                             placeholder=   "write your status/description">
                            {{ $user->description }}
                        </div>
                        <div class="row">
                            <h5 class="col-xs-4" id="post_{!! $user->uuid !!}">
                                {!! $user->posts !!}
                                <small class="text-muted">posts</small>
                            </h5>
                            <h5 class="col-xs-4" id="follower_{!! $user->uuid !!}">
                                {!! $user->followers !!}
                                <small class="text-muted">follower</small>
                            </h5>
                            <h5 class="col-xs-4" id="following_{!! $user->uuid !!}">
                                {!! $user->following !!}
                                <small class="text-muted">following</small>
                            </h5>
                        </div>
                    </div>
                </div>

                <div ng-cloak>
                    <md-content>
                        <md-tabs md-dynamic-height md-border-bottom>
                            <md-tab label="topics created">
                                <md-content class="md-padding">
                                    @include('html.feed-list',compact('topics'));
                                </md-content>
                            </md-tab>
                            <md-tab label="replies">
                                <md-content class="md-padding">

                                    @foreach($userReplies as $reply)

                                        <md-card class="row">
                                            <md-card-header>
                                                <md-card-avatar>
                                                    <img class="md-user-avatar"
                                                         src="{{$reply->profile_img}}"/>
                                                </md-card-avatar>
                                                <md-card-header-text>
                                                    <span class="md-title">
                                                         <a href="/{{ $reply->displayname }}">
                                                             {{ $reply->firstname }}
                                                         </a>
                                                    </span>
                                                </md-card-header-text>
                                            </md-card-header>
                                            <div class="card-block">
                                                <blockquote class="blockquote">
                                                    <p class="m-b-0">
                                                        {!!  clean($reply->body)  !!}
                                                    </p>
                                                    <footer class="blockquote-footer">
                                                        replie to topic <cite title="{{ $reply->topic }}"> {{clean($reply->topic)  }} </cite>

                                                        <span am-time-ago="'{!! $reply->replycreated_at !!}' | amParse:'YYYY-MM-DD H:i:s'"></span>
                                                    </footer>
                                            </div>
                                        </md-card>

                                    @endforeach
                                </md-content>
                            </md-tab>

                            {{--PHOTOS--}}
                            <md-tab label="photos">
                                <md-content class="md-padding">

                                </md-content>
                            </md-tab>

                        </md-tabs>
                    </md-content>
                </div>

            </div>
        </div>
    </div>

@endsection