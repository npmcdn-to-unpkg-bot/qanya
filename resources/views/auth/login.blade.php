@extends('layouts.app')

@section('content')

    <div class="container" style="max-width: 660px;"">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Login</div>
                        <div class="panel-body">
                            <form class="form-horizontal" role="form" method="POST" action="{{ url('/login') }}">
                                {!! csrf_field() !!}

                                <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">

                                    <md-input-container md-no-float class="md-block">
                                        <input type="email" name="email" value="{{ old('email') }}" placeholder="Your email">

                                        @if ($errors->has('email'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('email') }}</strong>
                                            </span>
                                        @endif
                                    </md-input-container>

                                </div>

                                <div class="md-padding{{ $errors->has('password') ? ' has-error' : '' }}">

                                    <md-input-container md-no-float class="md-block">
                                        <input type="password" name="password" placeholder="Your Password">

                                        @if ($errors->has('password'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('password') }}</strong>
                                            </span>
                                        @endif
                                    </md-input-container>

                                </div>

                                <div>
                                    <md-checkbox ng-model="true" aria-label="remember" name="remember">
                                        Remember me
                                    </md-checkbox>
                                </div>

                                <div class="form-group">


                                    {{--<div class="col-md-6">
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="remember" checked> Remember Me
                                            </label>
                                        </div>
                                    </div>--}}
                                </div>

                                <div class="form-group md-block ">

                                    <md-button type="submit" class="md-primary md-raised btn-block">
                                        Login
                                    </md-button>

                                </div>
                            </form>

                            <md-button class="md-accent" aria-label="Join us" ng-href="{{ url('/register') }}">
                                Need an account? join us
                            </md-button>

                            <a href="{{ url('/password/reset') }}">Forgot Your Password?</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
            
@endsection
