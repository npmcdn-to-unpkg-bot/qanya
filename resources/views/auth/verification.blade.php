@extends('layouts.app')


@section('content')

<div layout="column" layout-align="center center">

    <img src="/assets/images/logo.jpg" width="100px">

    <md-content layout="column" layout-padding>
        @{{ 'KEY_V_SIGN_UP' | translate }}
        <form method="POST" action="{{ url('/confirm-verification') }}">
            {!! csrf_field() !!}
            <div layout="row">
                <md-input-container class="md-block" flex-gt-xs>
                    <label>@{{ 'KEY_CFM_CODE' | translate }}</label>
                    <input ng-model="postCtrl.email_confirmation_code">
                </md-input-container>
            </div>

            <div ng-if="postCtrl.email_is_confirmed ==1">
                @{{ 'KEY_CODE_INC' | translate }}
            </div>

            <div ng-init="postCtrl.codeSentMessage = false" ng-show="postCtrl.codeSentMessage">
                @{{ 'KEY_CODE_SENT' | translate }}
            </div>

            <div layout="row" layout-align="end center">
                <md-button class="md-primary"
                           ng-click="postCtrl.sendVerificationCode()">
                    @{{ 'KEY_RESEND_CODE' | translate }}</md-button>
                <md-button class="md-primary" ng-click="postCtrl.confirmVerification()">
                    @{{ 'KEY_CFM' | translate }}</md-button>
            </div>
        </form>

    </md-content>

</div>

@endsection