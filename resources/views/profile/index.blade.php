@extends('layouts.app')

@section('content')

    {!! $user !!}
    <div class="container"
         ng-controller="ProfileCtrl as profileCtrl">
        <div class="layoutSingleColumn">

            DEBUG
            <br>
            is user? {{  $is_user }}

            <div class="row">
                <img src="https://avatars3.githubusercontent.com/u/11863395?v=3&s=460"
                     class="img-responsive img-circle col-xs-4"
                     width="80px">

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
                    <div class="row">
                        <h4 class="col-xs-4" id="post_{!! $user->uuid !!}">12 posts</h4>
                        <h4 class="col-xs-4" id="follower_{!! $user->uuid !!}">12 follower</h4>
                        <h4 class="col-xs-4" id="following_{!! $user->uuid !!}">12 following</h4>
                    </div>
                    <div contenteditable="{{ $is_user }}"
                         class="lead "
                         id= "profileDescription"
                         ng-blur    =   "profileCtrl.updateDescription()"
                         placeholder=   "write your status/description">
                        {{ $user->description }}
                    </div>
                </div>
            </div>
            @include('html.feed-list',compact('topics'));
        </div>
    </div>

@endsection