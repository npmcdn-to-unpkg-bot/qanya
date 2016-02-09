<div class="container" ng-controller="PostCtrl as postCtrl">
    <md-content>
        <form action="/register-name" method="post">

            {!! csrf_field() !!}

            <md-card style="max-width: 600px">
                <div class="alert alert-info" role="alert">
                    <button type="button" class="close"
                            data-dismiss="alert"
                            aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    Warning! You can only change displayname once
                </div>

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
                    Save</md-button>

            </md-card>
        </form>
    </md-content>
</div>