{{-- Post Create topic --}}

<div ng-controller="PostCtrl as postCtrl" ng-cloak="">
    <md-card>
        <md-card-header>
            <md-card-avatar>
                <img class="md-user-avatar"
                     src="{{ Auth::user()->profile_img }}"/>
            </md-card-avatar>


            <md-card-header-text ng-show="postCtrl.spanWriteSomething=true">
                <span class="md-title">
                    <h5>
                        <md-button ng-click="postCtrl.showForm=true;
                                             postCtrl.spanUsername=true;
                                             postCtrl.spanWriteSomething=false">
                            @{{ 'KEY_WHAT_ON_UR_MIND' | translate }}
                        </md-button>
                    </h5>
                </span>
            </md-card-header-text>
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
                            <md-option value="3">@{{ 'KEY_QUESTION' | translate }}</md-option>
                            <md-option value="2">@{{ 'KEY_REVIEW' | translate }}</md-option>
                        </md-select>
                    </md-input-container>
                </div>


                {{-- TITILE--}}
                <md-input-container>
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
                     placeholder="@{{ 'KEY_WRITE_SOMETHING' | translate }}"
                     class="md-block md-padding md-body-1"
                     data-content="test"
                     ng-model="contentBody"
                     style="min-height:100px;
                            border-bottom: thin solid #FAFAFA;"
                     id="contentBody"></div>


                {{-- REVIEW --}}
                <div ng-if="postCtrl.postTypes == 2;" ng-init="postCtrl.reviewCriteria = [{id: 'choice1'}, {id: 'choice2'}];">

                    <md-input-container md-no-float class="md-block">
                        <input type="text" ng-model="postCtrl.reviewName" name=""
                               placeholder="@{{ 'KEY_REVIEW_NAME' | translate }}">
                    </md-input-container>

                    <fieldset  data-ng-repeat="criteria in postCtrl.reviewCriteria">
                        <div layout="row">
                            <div flex>
                                <md-input-container md-no-float class="md-block">
                                    <input type="text" ng-model="criteria.name" name=""
                                           placeholder="Criteria">
                                </md-input-container>
                            </div>

                            <div flex>
                                <md-slider-container>
                                    <span>&nbsp;</span>
                                    <md-slider flex min="1" max="10" ng-model="criteria.rating" aria-label="red" id="red-slider">
                                    </md-slider>
                                </md-slider-container>
                            </div>

                            <div flex>
                                <label class="md-title">@{{ criteria.rating}}</label>
                            </div>
                        </div>
                        <md-button flex
                                   class="md-hue1 md-fab md-mini" ng-show="$last"
                                   ng-click="postCtrl.removeChoice()">-</md-button>
                        <md-button flex
                                   class="md-primary" ng-show="$last"
                                   ng-click="postCtrl.addNewChoice()">@{{ 'KEY_ADD_FIELD' | translate }}</md-button>
                    </fieldset>

                </div>
                {{-- END REVIEW --}}

                {{-- TAGS --}}
                <md-chips ng-model="postCtrl.topicTags"
                          class="reading md-block"
                          placeholder="Enter a tag - sepperate by Enter key"
                          secondary-placeholder="+Tag"
                          readonly="false"></md-chips>

                {{-- Upload / Price Range and Location search --}}
                <div layout="row">

                    {{-- UPLOAD --}}
                    <div flow-init
                         flow-name="uploader.flow"
                         flow-file-added="!!{png:1,gif:1,jpg:1,jpeg:1}[$file.getExtension()]"
                         flow-files-added="postCtrl.processFiles($files,'#contentBody')">
                        <md-tooltip md-direction="bottom">
                            Images
                        </md-tooltip>
                        <md-button
                                aria-label="Upload images"
                                class="md-fab md-mini md-primary" flow-btn type="file" name="image">
                            <md-icon md-svg-src="/assets/icons/ic_insert_photo_white_24px.svg"></md-icon>
                        </md-button>
                    </div>

                    {{-- Price range --}}
                    <md-button
                            ng-click="postCtrl.showPriceRange($event)"
                            aria-label="Upload images"
                            class="md-fab md-mini md-primary"  name="money">
                        <md-tooltip md-direction="bottom">
                            Price Range
                        </md-tooltip>
                        <md-icon md-svg-src="/assets/icons/ic_attach_money_white_24px.svg"></md-icon>
                    </md-button>

                {{-- Location search --}}
                    <md-button
                            ng-click="postCtrl.locationSearch = true"
                            aria-label="Upload images"
                            class="md-fab md-mini md-primary"  name="money">
                        <md-tooltip md-direction="bottom">
                            Location
                        </md-tooltip>
                        <md-icon md-svg-src="/assets/icons/ic_place_white_24px.svg"></md-icon>
                    </md-button>
                </div>
                {{-- end upload / price range and location search --}}


                {{-- If user selected a location show it in the card below --}}
                <div ng-if="postCtrl.locationObject" layout="column">


                    <md-card>
                        <img ng-src="@{{ postCtrl.locationMap }}"
                             class="md-card-image img-fluid"
                             style="max-height:150px" alt="Washed Out">
                        <md-card-title>
                            <md-card-title-text>
                                <span class="md-headline">@{{ postCtrl.locationObject.name }}</span>
                            </md-card-title-text>
                        </md-card-title>
                        <md-card-content>
                            <p>
                                <br>
                                @{{ postCtrl.locationObject.location.street }}
                                <br>
                                @{{ postCtrl.locationObject.location.city }}
                                <br>
                                @{{ postCtrl.locationObject.location.country }}
                            </p>
                        </md-card-content>
                    </md-card>

                </div>

                {{-- Location search bar and serach results --}}
                <div layout="columnn"
                     ng-init="postCtrl.locationSearch = false"
                     ng-show="postCtrl.locationSearch" flex>

                    <md-content>
                        <md-list>
                            <md-subheader class="md-no-sticky">
                                <md-input-container class="md-block">
                                    <md-icon md-svg-src="/assets/icons/ic_search_black_24px.svg"
                                             class="search"></md-icon>
                                    <input ng-model="postCtrl.locationName" type="input"
                                           ng-keyup="postCtrl.searchLocation()"
                                           placeholder="Search">
                                </md-input-container>
                            </md-subheader>
                            <md-list-item class="md-3-line" ng-repeat="(key, value) in postCtrl.fbResponse.data"
                                          ng-click="postCtrl.addLocation(value);
                                                    postCtrl.locationSearch = false">
                                <md-icon md-svg-src="/assets/icons/ic_add_location_black_24px.svg"></md-icon>
                                <div class="md-list-item-text" layout="column">
                                    <h3>@{{ value.name }}</h3>
                                    <h4>@{{ value.location.street }}
                                        @{{ value.location.city }}
                                        @{{ value.location.country }}</h4>
                                </div>
                            </md-list-item>
                        </md-list>
                    </md-content>
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