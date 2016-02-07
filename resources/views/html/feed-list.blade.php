 {{-- Feed --}}


  <div>
   <a class="btn btn-link">follow+</a>
  </div>

 @foreach($topics as $topic)
  <div class="media panel md-padding">
   <p class="pull-right">
    <i class="fa fa-bookmark-o fa-2x pull-right"></i>
   </p>
   <p>
    {{ Carbon\Carbon::parse($topic->created_at)->diffForHumans() }}
   </p>
   <div class="media-body">
    <h3 class="media-heading">
     <a href="{{ url($topic->slug) }}" target="_blank">{{ $topic->topic }}</a>
    </h3>
    <p class="listing-article">
     {{ str_limit($topic->body,250) }}
    </p>
    <div>
     <div class="pull-left">
      <i class="fa fa-chevron-up"></i> 10
      <i class="fa fa-chevron-down"></i> 5
      <i class="fa fa-comment"></i> 20
     </div>
     <div class="pull-right">
      <img class="media-object img-fluid img-circle"
           width="32px"
           src="https://avatars3.githubusercontent.com/u/11863395?v=3&s=460"
           alt="...">
      {{--{{$topic->uid}}--}}

     </div>
    </div>
   </div>
  </div>
 @endforeach