{{-- Post Create topic --}}

<div ng-controller="PostCtrl as postCtrl" ng-cloak="">
    <md-card>
        <md-card-header>
            <md-card-avatar>
                <img class="md-user-avatar"
                     src="{{ Auth::user()->profile_img }}"/>
            </md-card-avatar>


            <md-card-header-text ng-show="postCtrl.spanWriteSomething">
                <span class="md-title">
                    <h5>
                        <md-button ng-init ="postCtrl.spanWriteSomething=true"
                                   ng-click="postCtrl.showForm=true;
                                             postCtrl.spanUsername=true;
                                             postCtrl.spanWriteSomething=false">
                            @{{ 'KEY_WHAT_ON_UR_MIND' | translate }}
                        </md-button>
                    </h5>
                </span>
            </md-card-header>

            <md-content layout-padding>
                <form class="md-padding animated slideInDown"
                      ng-init="postCtrl.showForm=false" ng-show="postCtrl.showForm">

                    <input type="hidden" name="_token" value="{{ csrf_token() }}">

                    <div flex-gt-sm="80">
                        <md-input-container >
                            <label>@{{ 'KEY_IT_IS_ABOUT' | translate }}</label>
                            <md-select ng-model="postCtrl.postTypes" name="postCategories">
                                <md-option value="1">@{{ 'KEY_STORY' | translate }}</md-option>
                                <md-option value="2">@{{ 'KEY_QUESTION' | translate }}</md-option>
                                <md-option value="3">@{{ 'KEY_REVIEW' | translate }}</md-option>
                            </md-select>
                        </md-input-container>
                    </div>

                    @{{ postCtrl.postTypes }}



                    {{-- TITILE--}}
                    <md-input-container flex>
                        <label>@{{ 'KEY_TOPIC' | translate }}</label>
                        <input ng-model="postCtrl.title"
                               class="reading"
                               name="postTitle" required autocomplete="off">
                    </md-input-container>


                    {{-- SELECT CHANNEL--}}
                    <div flex>
                        <md-input-container class="md-block" flex-gt-sm>
                            <label>@{{ 'KEY_SEL_CHN' | translate }}</label>
                            <md-select ng-model="postCtrl.categories" name="postCategories">
                                @foreach ($categories as $cate)
                                    <md-option value="{!! $cate->id!!}">{!! $cate->name !!}</md-option>
                                @endforeach
                            </md-select>
                        </md-input-container>
                    </div>


                    {{-- BODY --}}
                    <div contenteditable="true"
                         placeholder="Write something"
                         class="md-block md-padding md-body-1"
                         data-content="test"
                         ng-model="contentBody"
                         style="min-height:100px;
                                border-bottom: thin solid #ff0000;"
                         id="contentBody"></div>


                    {{-- REVIEW --}}
                    <div ng-if="postCtrl.postTypes == 2">

                        <fieldset  data-ng-repeat="criteria in postCtrl.reviewCriteria">
                            <input type="text" ng-model="criteria.name" name=""
                                   placeholder="Criteria">
                            <input type="number" max="10" min="1" ng-model="criteria.rating">

                            <button class="btn btn-danger-outline" ng-show="$last"
                                    ng-click="postCtrl.removeChoice()">-</button>
                        </fieldset>

                        <button class="btn btn-primary-outline"
                                ng-click="postCtrl.addNewChoice()">@{{ 'KEY_ADD_FIELD' | translate }}</button>
                    </div>


                    {{-- TAGS --}}
                    <md-chips ng-model="postCtrl.topicTags"
                              class="reading md-block"
                              placeholder="Enter a tag - sepperate by Enter key"
                              secondary-placeholder="+Tag"
                              readonly="false"></md-chips>


                    {{-- UPLOAD --}}
                    <div flow-init
                         flow-name="uploader.flow"
                         flow-file-added="!!{png:1,gif:1,jpg:1,jpeg:1}[$file.getExtension()]"
                         flow-files-added="postCtrl.processFiles($files,'#contentBody')">
                        <md-button
                                aria-label="Upload images"
                                class="md-fab md-mini md-primary" flow-btn type="file" name="image">
                            <md-icon md-svg-src="/assets/icons/ic_insert_photo_white_24px.svg"></md-icon>
                        </md-button>
                    </div>


                    {{-- POST BUTTON --}}
                    <div layout="row" layout-align="end center">
                        <md-button type="submit" ng-class="md-primary"
                                   ng-click="postCtrl.postTopic()">@{{ 'KEY_POST' | translate }}</md-button>
                    </div>
                </form>
            </md-content>
    </md-card>
</div>