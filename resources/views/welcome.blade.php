@extends('layouts.app')


@section('content')

    <section layout="row" flex>

        <md-content flex layout-padding>


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
                <md-tab label="@{{ 'KEY_MST_REC' | translate }}">
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

                            <min-feed-list data="profileCtrl.userBookmark"></min-feed-list>

                        </md-content>
                    </md-tab>

                    <md-tab label="@{{ 'KEY_HISTORY' | translate }}"
                            ng-click="profileCtrl.getUserHistory('{{Auth::user()->uuid}}')">
                        <md-content class="md-padding">

                            <min-feed-list data="profileCtrl.userHistory"></min-feed-list>

                        </md-content>
                    </md-tab>

                @endif
            </md-tabs>
        </md-content>

        <md-sidenav
                class="md-sidenav-right"
                md-component-id="right"
                md-is-locked-open="$mdMedia('gt-md')"
                md-disable-backdrop>



            <div layout="column" layout-fill layout-align="top center" ng-controller="PostCtrl as postCtrl">


                @include('html.profile-badge')

                @if(Auth::user())
                    <user-tags data="postCtrl.userTags"></user-tags>
                @endif

                @include('html.category-nav',compact('categories'))

            </div>
        </md-sidenav>
    </section>


@endsection
