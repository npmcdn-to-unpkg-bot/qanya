@extends('layouts.app')

@section('content')
<div layout="row" ng-controller="PostCtrl as postCtrl">
    <div flex="66" flex-offset="15">

        <md-content layout="column" layout-padding>
            Thanks for Sign up with Qanya!. Please activate your account by enter the code in the box.

            <form method="POST" action="{{ url('/confirm-verification') }}">
                {!! csrf_field() !!}
                <div layout="row">
                    <md-input-container class="md-block" flex-gt-xs>
                        <label>Confirmation Code</label>
                        <input ng-model="postCtrl.email_confirmation_code">
                    </md-input-container>
                </div>

                <div ng-if="postCtrl.email_is_confirmed ==1">
                    the code is incorrect
                </div>

                <div layout="row" layout-align="end center">
                    <md-button class="md-primary" ng-click="postCtrl.sendVerificationCode()">resend code</md-button>
                    <md-button class="md-primary" ng-click="postCtrl.confirmVerification()">confirmation</md-button>
                </div>
            </form>

        </md-content>

    </div>
</div>
@endsection