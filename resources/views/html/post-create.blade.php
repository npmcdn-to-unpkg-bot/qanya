{{-- Post Create topic --}}
<div ng-controller="PostCtrl as postCtrl" ng-cloak="">
<md-card class="md-padding" id="postFormCard">
    <md-card-title>
        <md-card-title-media>

        </md-card-title-media>
        <md-card-title-text>
                        <span class="md-subhead"
                              ng-init="postCtrl.spanWriteSomething=true"
                              ng-show="postCtrl.spanWriteSomething"
                              ng-click="postCtrl.showForm=true;
                                        postCtrl.spanUsername=true;
                                        postCtrl.spanWriteSomething=false">
                            Write something
                        </span>
                        <span class="md-subhead" ng-init="postCtrl.spanUsername = false"
                              ng-show="postCtrl.spanUsername">
                            {{ Auth::user()->name }}
                        </span>
            <span></span>
        </md-card-title-text>
    </md-card-title>

    <form
          ng-init="postCtrl.showForm=false"
          ng-show="postCtrl.showForm"
          ng-submit="postCtrl.postTopic()"
          class="card">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <md-input-container class="md-block" flex-gt-sm>
            <label>Size</label>
            <md-select ng-model="postCtrl.categories" name="postCategories">
                @foreach ($categories as $cate)
                    <md-option value="{!! $cate->id!!}">{!! $cate->name !!}</md-option>
                @endforeach
            </md-select>
        </md-input-container>

        {{-- TITILE--}}
        <md-input-container>
            <label>Title</label>
            <input ng-model="postCtrl.title" class="reading" name="postTitle" required autocomplete="off">
        </md-input-container>

        {{-- BODY --}}
        <div contenteditable="true"
             placeholder="Enter text here..."
             class="panel"
             data-content="test"
             ng-model="contentBody"
             id="contentBody"></div>

        {{-- TAGS --}}
        <md-chips ng-model="postCtrl.topicTags"
                  class="reading"
                  readonly="false"></md-chips>


        <div flow-init
             flow-name="uploader.flow"
             flow-files-added="postCtrl.processFiles($files)">
            <md-button class="md-fab md-mini" flow-btn type="file" name="image">Upload Images</md-button>
        </div>


        <md-button type="submit" class="md-raised md-primary">Submit</md-button>
    </form>
</md-card>
</div>