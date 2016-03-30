@extends('layouts.app')

@section('content')

    <div class="container" style="max-width: 660px;"">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="md-headline">@{{ 'KEY_LOGIN'  | translate }}</div>
                        <div class="panel-body">
                            <form class="form-horizontal" role="form" method="POST" action="{{ url('/login') }}">
                                {!! csrf_field() !!}
                                <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">

                                    <md-input-container md-no-float class="md-block">
                                        <input type="email" name="email" value="{{ old('email') }}"
                                               placeholder="@{{ 'KEY_EMAIL' | translate }}">

                                        @if ($errors->has('email'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('email') }}</strong>
                                            </span>
                                        @endif
                                    </md-input-container>

                                </div>

                                <div class="md-padding{{ $errors->has('password') ? ' has-error' : '' }}">

                                    <md-input-container md-no-float class="md-block">
                                        <input type="password" name="password"
                                               placeholder="@{{ 'KEY_PASSWORD' | translate }}">

                                        @if ($errors->has('password'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('password') }}</strong>
                                            </span>
                                        @endif
                                    </md-input-container>

                                </div>

                                {{--<div>
                                    <md-checkbox ng-model="true" aria-label="remember" name="remember">
                                        @{{ 'KEY_REMEMBER_ME' | translate }}
                                    </md-checkbox>
                                </div>--}}

                                <div class="form-group">
                                    <div class="col-md-6">
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="remember" checked>
                                                @{{ 'KEY_REMEMBER_ME' | translate }}
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group md-block ">

                                    <md-button type="submit" class="md-primary md-raised btn-block">
                                        @{{ 'KEY_LOGIN'  | translate }}
                                    </md-button>

                                </div>
                            </form>

                            <md-button class="md-accent" aria-label="Join us" ng-href="{{ url('/register') }}">
                                @{{ 'KEY_NO_ACCT_REGISTER' | translate }}
                            </md-button>

                            <a href="{{ url('/password/reset') }}">@{{ 'KEY_FORGOT_PWD' | translate }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
            
@endsection
