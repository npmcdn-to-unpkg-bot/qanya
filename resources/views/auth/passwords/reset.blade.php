@extends('layouts.app')

@section('content')
    <div layout="column" layout-align="center center">

        <img src="/assets/images/logo.jpg" width="100px">

        <form class="form-horizontal" role="form" method="POST" action="{{ url('/password/reset') }}">
            {!! csrf_field() !!}

            <input type="hidden" name="token" value="{{ $token }}">

            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">


                <md-input-container md-no-float class="md-block">
                    <input
                            type="email" name="email" value="{{ $email or old('email') }}">

                    @if ($errors->has('email'))
                        <span class="help-block">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                    @endif
                </md-input-container>
            </div>


            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                <md-input-container md-no-float class="md-block">
                    <input type="password" class="form-control" name="password"
                           placeholder="@{{ 'KEY_PASSWORD' | translate }}">

                    @if ($errors->has('password'))
                        <span class="help-block">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                    @endif
                </md-input-container>
            </div>


            <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                <md-input-container md-no-float class="md-block">
                    <input type="password" class="form-control" name="password_confirmation"
                           placeholder="@{{ 'KEY_NEW_PWD_C' | translate }}">

                    @if ($errors->has('password_confirmation'))
                        <span class="help-block">
                                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                                    </span>
                    @endif
                </md-input-container>
            </div>


            <div class="form-group">
                <md-button type="submit" class="md-primary md-raised btn-block">
                    @{{ 'KEY_SAVE' | translate }}
                </md-button>
            </div>
        </form>

    </div>

@endsection
