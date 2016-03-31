{{-- Post Create topic --}}

<div ng-controller="PostCtrl as postCtrl" ng-cloak="" class="row">
    <md-card class="">
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
            <form class="md-padding"
                  ng-init="postCtrl.showForm=false" ng-show="postCtrl.showForm">

                <input type="hidden" name="_token" value="{{ csrf_token() }}">


                <!-- topic type -->
                <div class="btn-group" data-toggle="buttons">

                    <!-- Questions-->
                    <label class="btn btn-success-outline"
                           ng-click="postCtrl.postTypes = 3;
                           postCtrl.reviewCriteria = false;
                           postCtrl.showReview=false" value="question">
                        <input type="radio" autocomplete="off" checked>
                        <i class="ion-help"></i>
                        question
                    </label>

                    <!-- Translate -->
                    <label class="btn btn-success-outline"
                           ng-click="postCtrl.postTypes = 1;
                           postCtrl.reviewCriteria = false;
                           postCtrl.showReview=false" value="topic">
                        <input type="radio" autocomplete="off" >
                        <i class="ion-chatbubble-working"></i>
                        topic
                    </label>

                    <!-- Review -->
                    <label class="btn btn-success-outline"
                           ng-click="postCtrl.postTypes = 2;
                           postCtrl.reviewCriteria = [{id: 'choice1'}, {id: 'choice2'}];
                           postCtrl.showReview=true" value="review">
                        <input type="radio" autocomplete="off" >
                        <i class="ion-checkmark-round"></i>
                        review
                    </label>
                </div>


                {{-- TITILE--}}
                <md-input-container class="md-block" flex-gt-sm>
                    <label>Title</label>
                    <input ng-model="postCtrl.title"
                           class="reading"
                           name="postTitle" required autocomplete="off">
                </md-input-container>



                <div layout-gt-sm="row">
                <md-input-container class="md-block" flex-gt-sm>
                    <label>Channel</label>
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
                     class="panel md-block md-padding"
                     data-content="test"
                     ng-model="contentBody"
                     style="min-height:100px;
                                    border-color:#ebf2f8;
                                    border-style: solid;
                                    border-width: 2px "
                     id="contentBody"></div>


                @{{ postCtrl.reviewCriteria  }}
                <div ng-show="postCtrl.showReview" ng-init="postCtrl.showReview=false">

                    <fieldset  data-ng-repeat="criteria in postCtrl.reviewCriteria">
                        <input type="text" ng-model="criteria.name" name=""
                               placeholder="Criteria">
                        <input type="number" max="10" min="1" ng-model="criteria.rating">

                        <button class="btn btn-danger-outline" ng-show="$last"
                                ng-click="postCtrl.removeChoice()">-</button>
                    </fieldset>

                    <button class="btn btn-primary-outline"
                            ng-click="postCtrl.addNewChoice()">Add fields</button>
                </div>

                {{-- TAGS --}}
                <md-chips ng-model="postCtrl.topicTags"
                          class="reading md-block"
                          placeholder="Enter a tag - sepperate by Enter key"
                          secondary-placeholder="+Tag"
                          readonly="false"></md-chips>


                <div flow-init
                     flow-name="uploader.flow"
                     flow-file-added="!!{png:1,gif:1,jpg:1,jpeg:1}[$file.getExtension()]"
                     flow-files-added="postCtrl.processFiles($files,'#contentBody')">
                    <md-button
                            aria-label="Upload images"
                            class="md-fab md-mini" flow-btn type="file" name="image">
                        <md-icon md-svg-src="/assets/icons/ic_insert_photo_white_24px.svg"></md-icon>
                    </md-button>
                </div>


                <md-button type="submit" class="md-raised md-primary"
                           ng-click="postCtrl.postTopic()">Submit</md-button>
            </form>
            </md-content>
    </md-card>
</div>