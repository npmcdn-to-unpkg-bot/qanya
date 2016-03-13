{{-- Topic reply list content --}}
@foreach($data as $reply)
    <md-list-item class="md-3-line">
        <img src="{{ $reply->profile_img }}"
             class="md-avatar"
        />
        <div class="md-list-item-text" layout="column">
            <h3>
                <a href="/{{ $reply->displayname }}" target="_blank">
                    {{$reply->firstname}}
                </a>
                <small>
                    - {!! Carbon\Carbon::parse($reply->created_at)->diffForHumans() !!}
                </small>
            </h3>
            <h4> {!! HTML::decode($reply->body) !!} </h4>

        </div>
    </md-list-item>
@endforeach