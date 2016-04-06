@extends('layouts.app')

@section('content')
    <div layout="column" layout-align="center center">
        {{--<div class="panel-heading">Register</div>--}}
        <img src="/assets/images/logo.jpg" width="100px">

            <form role="form" method="POST" action="{{ url('/register') }}">
                {!! csrf_field() !!}

                <div layout-gt-sm="row">
                    <div class="form-group{{ $errors->has('firstname') ? ' has-error' : '' }}">
                        <md-input-container md-no-float class="md-block">
                            <input type="text" class="form-control" name="firstname"
                                   placeholder="firstname"
                                   value="{{ old('firstname') }}">

                            @if ($errors->has('firstname'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('firstname') }}</strong>
                                </span>
                            @endif
                        </md-input-content>
                    </div>

                    <div class="form-group{{ $errors->has('lastname') ? ' has-error' : '' }}">
                        <md-input-container md-no-float class="md-block">
                            <input type="text" class="form-control" name="lastname"
                                   placeholder="lastname"
                                   value="{{ old('lastname') }}">

                            @if ($errors->has('lastname'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('lastname') }}</strong>
                                </span>
                            @endif
                        </md-input-content>
                    </div>
                </div>


                <div layout-gt-sm="row" layout="column">
                    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                        <md-input-container md-no-float class="md-block">
                            <input type="email" class="form-control" name="email"
                                   placeholder="E-Mail"
                                   value="{{ old('email') }}">
                            @if ($errors->has('email'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                            @endif
                        </md-input-content>
                    </div>
                </div>

                <div layout-gt-sm="row">
                    <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                        <md-input-container md-no-float class="md-block">
                            <input type="password" class="form-control" name="password"
                                  placeholder="Password">
                            @if ($errors->has('password'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                            @endif
                        </md-input-content>
                    </div>
                </div>

                <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                    <md-input-container md-no-float class="md-block">
                        <input type="password" class="form-control" name="password_confirmation"
                               placeholder="Confirm Password">
                        @if ($errors->has('password_confirmation'))
                            <span class="help-block">
                                <strong>{{ $errors->first('password_confirmation') }}</strong>
                            </span>
                        @endif
                    </md-input-content>
                </div>

                <div class="form-group md-block ">
                    <md-button type="submit" class="md-primary md-raised btn-block">
                        Register
                    </md-button>
                </div>
            </form>
</div>
@endsection
