{{-- topic-listing --}}


@foreach($topics as $topic)
    <div class="card">
        <div class="media md-margin">
            <div class="media-left">
                <a href="#">
                    <img class="media-object img-fluid img-circle"
                         width="60px"
                         src="https://avatars3.githubusercontent.com/u/11863395?v=3&s=460"
                         alt="...">
                </a>
            </div>
            <div class="media-body">
                <h4 class="media-heading">
                    <a href="/{{ $topic->displayname }}">
                        {{ $topic->firstname }}
                    </a>
                </h4>
                {{ $topic->description }}
                <div>
                    <b>10</b> post
                </div>
            </div>
        </div>
        {{--<img class="card-img-top" data-src="..." alt="Card image cap">--}}
        <div class="card-block">
            <h4 class="card-title">
                <a href="{!! $topic->displayname !!}/{!! $topic->topic_slug!!}">
                {{$topic->topic}}
                </a>
            </h4>
            <p class="card-text">
                {!! nl2br($topic->body) !!}
                </p>
        </div>
    </div>
@endforeach
