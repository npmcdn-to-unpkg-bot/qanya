@extends('layouts.app')

@section('content')
    <div class="container" ng-controller="PostCtrl as postCtrl">

        <div class="col-xs-12 col-sm-8">
            <span class="md-title">
                    #{{ $tag }}
                </span>

            @if(Auth::user())
                <a class="btn btn-success-outline pull-right"
                   ng-init="postCtrl.followTagStatus('{{Auth::user()->uuid}}', '{{$tag}}')"
                   ng-click="postCtrl.followTag('{{Auth::user()->uuid}}','{{$tag}}')">
                    @{{ postCtrl.tagFollowStatus }}
                </a>
            @endif

            @include('html.feed-list',compact('topics'))
        </div>
        <div class="col-xs-12 col-sm-4">
            Other tags
        </div>
    </div>
@endsection