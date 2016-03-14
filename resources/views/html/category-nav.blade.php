<ul class="nav nav-pills">
    @foreach ($categories as $cate)
        <li class="nav-item btn-success-outline"
            role="presentation">
            <a href="/channel/{{ $cate->slug }}" class="btn btn-success-outline md-margin"
               ng-click="postCtrl.getFeedCate('{{ $cate->slug }}','{{$cate->name}}');
                                    postCtrl.feedFollowStatus('{{ $cate->slug }}')">
                {{$cate->name}}</a>
        </li>
    @endforeach
</ul>