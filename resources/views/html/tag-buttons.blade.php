@if(!empty($data))
    Tags you are following
    @foreach($data as $tag=>$value)
        <a href="/tag/{{$tag}}" class="badge" target="_blank">#{{$tag}}</a>
    @endforeach
@else
    You are not following any tags yet
@endif