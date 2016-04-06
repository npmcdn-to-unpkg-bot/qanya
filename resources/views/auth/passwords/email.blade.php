@extends('layouts.app')

<!-- Main Content -->
@section('content')
    <div layout="column" layout-align="center center">

        <img src="/assets/images/logo.jpg" width="100px">

            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif

            <form class="form-horizontal" role="form" method="POST" action="{{ url('/password/email') }}">
                {!! csrf_field() !!}

                <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">

                    <md-input-container md-no-float class="md-block">
                        <input type="email" class="form-control" name="email" value="{{ old('email') }}"
                               placeholder="@{{ 'KEY_EMAIL' | translate }}">

                        @if ($errors->has('email'))
                            <span class="help-block">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                        @endif
                    </md-input-container>

                </div>

                <div class="form-group">

                    <md-button type="submit" class="md-primary md-raised btn-block">
                        @{{ 'KEY_PWD_RESET' | translate }}
                    </md-button>

                </div>
            </form>
        </div>

    </div>
@endsection
