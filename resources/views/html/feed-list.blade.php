 {{-- Feed --}}


<div ng-controller="PostCtrl as postCtrl">
  @foreach($topics as $topic)

   <md-card class="row">
    <md-card-header>    
     <md-card-avatar>
      <img class="md-user-avatar" src="https://avatars3.githubusercontent.com/u/11863395?v=3&s=460"/>
     </md-card-avatar>
      <md-card-header-text>    
        <span class="md-title">
         <a href="/{{ $topic->displayname }}">
            {{ $topic->firstname }}
         </a>
         {{ $topic->displayname }} -
         {{ Carbon\Carbon::parse($topic->topic_created_at)->diffForHumans() }}
        </span>
        <span class="md-subhead">
          {{ $topic->description }}
        </span>

      </md-card-header-text>
      <p class="pull-right">
        <md-button class="md-icon-button" aria-label="More">
          <i class="md-fab md-mini fa fa-bookmark-o fa-2x pull-right" ng-click="postCtrl.bookMark($event)"></i>
        </md-button>
      </p>  
    </md-card-header>  

    <div class="card-block">
      <h4 class="card-title">
        <a href="{{ url($topic->displayname.'/'.$topic->topic_slug) }}"
          target="_blank">{{ $topic->topic }}</a>
      </h4>    
      <p class="card-text">
        {!! nl2br(str_limit($topic->body,250)) !!}      
        <?php
       $tags = explode(',',$topic->tags);?>
        @if($tags)
          <div>
            @foreach($tags as $tag)
              <a href="/tag/{{$tag}}">#{{$tag}}</a>
            @endforeach
         </div>
        @endif
      </p>
        
      <a href="#" class="card-link"><i class="fa fa-chevron-up"></i>  99</a>
      <a href="#" class="card-link"><i class="fa fa-chevron-down"></i>  99</a>
      <a href="#" class="card-link"><i class="fa fa-comment-o"></i>  99</a>    
    </div>
    
   </md-card>

   @endforeach
 </div>