@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="col-xs-12 col-sm-8">
            @include('html.feed-list',compact('topics'))
        </div>
        <div class="col-xs-12 col-sm-4">
            Other tags
        </div>
    </div>
@endsection