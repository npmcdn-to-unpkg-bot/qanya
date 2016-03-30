@extends('layouts.app')


@section('content')

    <div class="row" >

        <div class="col-md-7">

            @if(Auth::user())
                @include('html.post-create',compact('categories'))
            @endif


            <md-tabs md-dynamic-height md-border-bottom >
                <md-tab label="@{{ 'KEY_LATEST_FEED' | translate }}">
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

                    <md-tab label="@{{ 'KEY_BOOKMARK' | translate }}"
                            ng-click="profileCtrl.getUserBookmark('{{Auth::user()->uuid}}')">
                        <md-content class="md-padding">
                            <div id="userBookmark">

                                <min-feed-list data="profileCtrl.userBookmark"></min-feed-list>
                                
                            </div>
                        </md-content>
                    </md-tab>

                    <md-tab label="@{{ 'KEY_HISTORY' | translate }}"
                            ng-click="profileCtrl.getUserHistory('{{Auth::user()->uuid}}')">
                        <md-content class="md-padding">
                            <div id="userViewHistory">
                                @{{ profileCtrl.userHistory }}
                            </div>
                        </md-content>
                    </md-tab>

                @endif
            </md-tabs>


        </div>

        <div class="col-md-5 col-xs-12" ng-controller="PostCtrl as postCtrl">
            
            @include('html.profile-badge')

            @if(Auth::user())
                <div class="media panel md-padding">
                    <div ng-init="postCtrl.userTagList('{{Auth::user()->uuid}}')"
                         id="userTagList">
                    </div>
                </div>

                <div ng-init="postCtrl.userTagList('{{Auth::user()->uuid}}')" id="userTagList">
                </div>
                <div ng-controller="PostCtrl as postCtrl"
                     ng-init="postCtrl.userTagList('{{Auth::user()->uuid}}')"
                     id="userTagList"></div>

            @endif

            @include('html.category-nav',compact('categories'))

        </div>
    </div>


@endsection
