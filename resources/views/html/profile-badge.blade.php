{{-- If user is login then show their current info and statuses --}}
 @if(Auth::user())
    <div ng-controller="ProfileCtrl as profileCtrl">
        <div class="media panel md-padding" ng-init="profileCtrl.getUserStat('{{Auth::user()->uuid}}')">
            <div class="media-body">
                <h4 class="media-heading">
                    <a href="/{!! Auth::user()->displayname !!}">
                        {{ Auth::user()->firstname }}
                    </a>
                </h4>
                {{ Auth::user()->description }}
                 <div>

                     <b> {{ profileCtrl.user_stat_<?=str_replace('-','',Auth::user()->uuid)?>.upvote }}</b>
                     @{{ 'KEY_UPVOTE' | translate }}

                     <b> {{ Auth::user()->posts }}</b>
                     @{{ 'KEY_POST' | translate }}

                     <b> {{ Auth::user()->following }}</b>
                     @{{ 'KEY_FOLLOWER' | translate }}
                </div>
            </div>
            <div class="media-right">
                <a href="/{!! Auth::user()->displayname !!}">
                    <img class="media-object"
                         width="80px"
                         src="{{ Auth::user()->profile_img }}"
                         alt="{{ Auth::user()->firstname }}">
                </a>
            </div>
        </div>

        <div class="media panel md-padding">
            <div ng-init="postCtrl.userTagList('{{Auth::user()->uuid}}')"
                 id="userTagList">
            </div>
        </div>
    </div>
@endif
