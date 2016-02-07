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

    <form action="/postTopic" method="post"
          ng-init="postCtrl.showForm=false"
          ng-show="postCtrl.showForm"
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
        <md-input-container>
            <label>Title</label>
            <input ng-model="postCtrl.title" name="postTitle" required autocomplete="off">
        </md-input-container>
        <md-input-container class="md-block">
            <label>Body</label>
            <textarea
                    class="reading"
                    ng-model="postCtrl.body" name="postBody" rows="5" md-select-on-focus required></textarea>
        </md-input-container>


        <input id="fileInput" name="image" type="file" multiple>


        <md-button type="submit" class="md-raised md-primary">Submit</md-button>
    </form>
</md-card>
</div>