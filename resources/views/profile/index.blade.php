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
                @include('html.feed-list',compact('topics'));
            </div>
        </div>
    </div>

@endsection