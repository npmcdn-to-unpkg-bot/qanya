{{-- Channel list --}}
<ul class="nav nav-pills">
    @foreach ($categories as $cate)

            <md-button href="/channel/{{ $cate->slug }}" class="md-margin md-mini"
               ng-click="postCtrl.getFeedCate('{{ $cate->slug }}','{{$cate->name}}');
                                    postCtrl.feedFollowStatus('{{ $cate->slug }}')">
                {{$cate->name}}</md-button>

    @endforeach
</ul>