@extends('email_layout')

@section('content')

    Hello {{ $recipient->firstname }}

    <p>

        You have a new follower!
        {{ $sender->firstname }} is following you!
    </p>


    Thanks,
    <br>
    The Qanya Team


@stop
