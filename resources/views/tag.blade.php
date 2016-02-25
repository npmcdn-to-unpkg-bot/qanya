@extends('layouts.app')

@section('content')
    <div class="container">
        @include('html.feed-list',compact('topics'));
    </div>
@endsection