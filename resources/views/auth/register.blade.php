@extends('layouts.app')

@section('content')
<div class="container" style="max-width: 660px;"">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Register</div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/register') }}">
                        {!! csrf_field() !!}

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

                        <div class="form-group">
                            <md-button type="submit" class="md-raised md-primary">
                                Register
                            </md-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
