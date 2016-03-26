{{-- If user is login then show their current info and statuses --}}
@if(Auth::user())
    <?php
            echo $user_uuid = Auth::user()->uuid;
    ?>
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
                    <b> {{ Auth::user()->posts }}</b> posts
                    <b> @{{ profileCtrl.userFollower_$user_uuid }}</b> followers
                    <b> @{{ profileCtrl.userUpvoted }}</b> upvote
                </div>
            </div>
            <div class="media-right">
                <a href="#">
                    <img class="media-object"
                         width="80px"
                         src="{{ Auth::user()->profile_img }}"
                         alt="...">
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