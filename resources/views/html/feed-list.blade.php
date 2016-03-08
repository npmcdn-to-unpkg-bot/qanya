 {{-- Feed --}}


<div ng-controller="PostCtrl as postCtrl">
  @foreach($topics as $topic)
   <md-card class="row">
    <md-card-header>
     <md-card-avatar>
      <img class="md-user-avatar"
           src="{{$topic->profile_img}}"/>
     </md-card-avatar>
      <md-card-header-text>
        <span class="md-title">
         <a href="/{{ $topic->displayname }}">
            {{ $topic->firstname }}
         </a>
         {{ $topic->displayname }} -
        <span am-time-ago="'{!! $topic->topic_created_at !!}' | amParse:'YYYY-MM-DD H:i:s'"></span>
{{--         {{ Carbon\Carbon::parse($topic->topic_created_at)->diffForHumans() }}--}}
        </span>
        <p class="md-subhead">
          {!! HTML::decode($topic->description) !!}
        </p>

      </md-card-header-text>
      <p class="pull-right">
        Travel
      </p>
    </md-card-header>

    <div class="card-block">
      <h4 class="card-title">
        <a href="{{ url($topic->displayname.'/'.$topic->topic_slug) }}"
          target="_blank">{!! HTML::decode($topic->topic) !!}</a>
      </h4>

        <div class="card-text">
          {!! clean(str_limit(nl2br($topic->text),250)) !!}
            <?php
           $tags = explode(',',$topic->tags);?>
            @if($tags)
              <div>
                @foreach($tags as $tag)
                  <a href="/tag/{{$tag}}">#{{$tag}}</a>
                @endforeach
             </div>
            @endif
        </div>

        @include('html.topic-tally',['topics_uid' => $topic->topics_uid,
                                 'uuid'       => $topic->topic_uuid])

    </div>
   {{-- end --}}

    <script>
        socket.on("upv_cnt_{{$topic->topic_uuid}}:App\\Events\\TopicUpvote", function(message){
            $('#upv_cnt_{!! $topic->topic_uuid !!}').text(message.upv_cnt);
        });
    </script>
   </md-card>

   @endforeach
 </div>