@extends('layouts.app')


@section('content')

    <div class="row" >

        <div class="col-md-7">

            @if(Auth::user())
                @include('html.post-create',compact('categories'))
            @endif

            <md-tabs md-dynamic-height md-border-bottom >
                <md-tab label="Latest feed">
                    <md-content class="md-padding">
                        <div id="homeFeed">
                            @include('html.feed-list',compact('topics'))
                        </div>
                    </md-content>
                </md-tab>
                <md-tab label="Most recomended">
                    <md-content class="md-padding">
                        <div id="homeFeed">
                            @include('html.feed-list',compact('topics'))
                        </div>
                    </md-content>
                </md-tab>
                {{-- IF user login --}}
                @if(Auth::user())
                {{--<div ng-controller="ProfileCtrl as profileCtrl">--}}
                    <md-tab label="bookmark" ng-click="profileCtrl.getUserBookmark('{{Auth::user()->uuid}}')">
                        <md-content class="md-padding">
                            <div id="userBookmark">
                                @{{profileCtrl.userBookmark}}
                            </div>
                        </md-content>
                    </md-tab>

                    <md-tab label="history" ng-click="profileCtrl.getUserHistory('{{Auth::user()->uuid}}')">
                        <md-content class="md-padding">
                            <div id="userViewHistory">
                                @{{ profileCtrl.userHistory }}
                            </div>
                        </md-content>
                    </md-tab>
                {{--</div>--}}
                @endif
            </md-tabs>


        </div>

        <div class="col-md-5 col-xs-12">

            >>templates
            {{--<profile-badge></profile-badge>--}}
            <<<
            @include('html.profile-badge')
            @include('html.category-nav',compact('categories'))

        </div>
    </div>


@endsection
