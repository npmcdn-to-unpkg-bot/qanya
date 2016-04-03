@extends('email_layout')

@section('content')

Hello {{ $recipient->firstname }}

<p>
There is a new reply in <a href="/{{$topic->displayname}}/{{$topic->slug}}">{{ strip_tags($topic->topic)}}</a>

<p>
    <img src="{{$recipient->profile_img}}" width="55px">
    <a href="/{{$sender->displayname}}/">
        {{ $sender->firstname }}
    </a>
post

the following message "{{ strip_tags($replyObj->body) }}"
</p>

</p>

Thanks,
<br>
The Qanya Team


@stop
