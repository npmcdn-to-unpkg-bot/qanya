@extends('layouts.app')

@section('content')

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
                    <h1 class="display-4">
                        {{ $user->name }}
                        <p>
                        <small>
                            {{ $user->displayname }}
                        </small>
                        </p>
                    </h1>
                    <div contenteditable="{{ $is_user }}"
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