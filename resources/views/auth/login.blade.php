@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <md-card>
                <md-card-title>
                    <md-card-title-text class="md-headline">
                        Login
                    </md-card-title-text>
                </md-card-title>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/login') }}">
                        {!! csrf_field() !!}

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <div class="col-md-8">
                                <md-input-container class="md-block">
                                    <label>Your email</label>
                                    <input type="email" name="email" value="{{ old('email') }}">

                                    @if ($errors->has('email'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                    @endif
                                </md-input-container>
                            </div>
                        </div>

                        <div class="md-padding{{ $errors->has('password') ? ' has-error' : '' }}">

                            <div class="col-md-8">
                                <md-input-container class="md-block">
                                    <label>Your Password</label>
                                    <input type="password" name="password">

                                    @if ($errors->has('password'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('password') }}</strong>
                                        </span>
                                    @endif
                                </md-input-container>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="remember"> Remember Me
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <md-button type="submit" class="md-raised md-primary">
                                    Login
                                </md-button>

                                <a class="btn btn-link" href="{{ url('/password/reset') }}">Forgot Your Password?</a>
                            </div>
                        </div>
                    </form>
                </div>
            </md-card>
        </div>
    </div>
</div>
@endsection
