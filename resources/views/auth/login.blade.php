@extends('layouts.app')

@section('content')

    <div style="margin: 0 auto;max-width: 660px;">
        <md-card class="row" style="">
            
            <md-card-title layout-align="start center" layout="column">
                <md-card-title-text class="md-headline">
                    Login
                </md-card-title-text>
            </md-card-title>

            <div class="md-padding" layout-align="start center" layout="column">

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

                    <div class="form-group">
                        <div class="col-md-6">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="remember"> Remember Me
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-6">
                            <md-button type="submit" class="md-raised md-primary">
                                Login
                            </md-button>                            
                        </div>
                    </div>
                </form>
                <a href="{{ url('/password/reset') }}">Forgot Your Password?</a>
            </div>
        </md-card>
    </div>
            
@endsection
