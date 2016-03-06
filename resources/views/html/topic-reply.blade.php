{{-- Topic reply list content --}}

<md-list-item class="md-3-line highlight">
    <img src="{{ $data->profile_img }}"
         class="md-avatar"
    />
    <div class="md-list-item-text" layout="column">
        <h3>
            <a href="/{{ $data->displayname }}" target="_blank">
                {{$data->firstname}}
            </a>
            <small>
                - {!! Carbon\Carbon::parse($data->replycreated_at)->diffForHumans() !!}
{{--                - <span am-time-ago="'{!! $data->replycreated_at !!}' | amParse:'YYYY-MM-DD HH:mm:ss'"></span>--}}
            </small>
        </h3>
        <h4> {!! HTML::decode($data->body) !!} </h4>

    </div>
</md-list-item>
