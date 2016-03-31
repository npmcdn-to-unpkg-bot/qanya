<div class="container" ng-controller="PostCtrl as postCtrl">
    <md-content layout="row" layout-align="center">
        <form action="/register-name" method="post">

            {!! csrf_field() !!}


            <p class="md-subhead md-hue-1">
                @{{ 'KEY_NAME_CHG_ONCE' | translate }}
            </p>

            <div ng-show="postCtrl.displayname.alert"
                 ng-init="postCtrl.displayname.alert= false"
                 class="alert alert-danger"
                 role="alert">
                name already exist
            </div>

            <md-input-container class="md-block">
                <label>Display name</label>
                <input type="text"
                       ng-model="postCtrl.displayname.text"
                       autocomplete="off"
                       name="displayname"
                       required>
                <md-button ng-click="postCtrl.checkDisplayname()">
                    check</md-button>
            </md-input-container>

            <md-button ng-show="postCtrl.displayname.saveBtn"
                       type="submit">
                @{{ 'KEY_SAVE' | translate }}
            </md-button>


        </form>
    </md-content>
</div>