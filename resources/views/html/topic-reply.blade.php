{{-- Topic reply list content --}}

<md-list-item class="md-3-line">
    <img src="https://avatars3.githubusercontent.com/u/11863395?v=3&s=460"
         class="md-avatar"
    />
    <div class="md-list-item-text" layout="column">
        <h3> Username </h3>
        <h4> {{ $data['data']['body'] }} </h4>
        {!! Carbon\Carbon::parse($data['data']['created_at'])->diffForHumans() !!}
    </div>
</md-list-item>
