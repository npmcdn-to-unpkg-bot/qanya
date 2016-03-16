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
                            Write something
                        </md-button>

                    </h5>
                    </md-card-header-text>
                </span>
            </md-card-header>

            <md-content layout-padding>
            <form class="md-padding"
                  ng-init="postCtrl.showForm=false" ng-show="postCtrl.showForm">

                <input type="hidden" name="_token" value="{{ csrf_token() }}">


                {{-- TITILE--}}
                <md-input-container class="md-block" flex-gt-sm>
                    <label>Title</label>
                    <input ng-model="postCtrl.title"
                           class="reading"
                           name="postTitle" required autocomplete="off">
                </md-input-container>


                <md-input-container class="md-block" flex-gt-sm>
                    <label>Types</label>
                    <md-select ng-model="postCtrl.postTypes" name="postTypes">

                        <md-option value="1">กระทู้</md-option>
                        <md-option value="2">รีวิว</md-option>
                        <md-option value="3">คำถาม</md-option>

                    </md-select>
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

                {{-- TAGS --}}
                <md-chips ng-model="postCtrl.topicTags"
                          class="reading md-block"
                          placeholder="Enter a tag - sepperate by Enter key"
                          secondary-placeholder="+Tag"
                          readonly="false"></md-chips>


                <div flow-init
                     flow-name="uploader.flow"
                     flow-files-added="postCtrl.processFiles($files,'#contentBody')">
                    <md-button class="md-fab md-mini" flow-btn type="file" name="image">
                        <md-icon md-svg-src="/assets/icons/ic_insert_photo_white_24px.svg"></md-icon>
                    </md-button>
                </div>


                <md-button type="submit" class="md-raised md-primary"
                           ng-click="postCtrl.postTopic()">Submit</md-button>
            </form>
            </md-content>
    </md-card>
</div>