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
        <md-button class="md-icon-button" aria-label="More">
          <i class="md-fab md-mini fa fa-bookmark-o fa-2x pull-right" ng-click="postCtrl.bookMark($event)"></i>
        </md-button>
      </p>
    </md-card-header>

    <div class="card-block">
      <h4 class="card-title">
        <a href="{{ url($topic->displayname.'/'.$topic->topic_slug) }}"
          target="_blank">{!! HTML::decode($topic->topic) !!}</a>
      </h4>
      <div class="card-text">
{{--          {!! HTML::entities(str_limit(nl2br($topic->body),250)) !!}--}}
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

    {{-- Upvote/Downvote/Comment --}}
        <a href="#" id="upvote_btn_status_{{ $topic->topic_uuid }}"
            class="card-link"
            ng-init="postCtrl.upvoteTally('{{ $topic->topic_uuid }}')"
            ng-click="postCtrl.upvote('{{ $topic->topic_uuid }}','{!! $topic->topics_uid !!}')">
                <i class="fa fa-chevron-up"></i>
                    <span id="upv_cnt_{{$topic->topic_uuid}}">
                        {{--{!! $topic->upvote !!}--}}
                        {{ postCtrl.upvote_<?= $topic->topic_uuid?> }}
                    </span>
      </a>
        <a href="#" id="dwnvote_btn_status_{{ $topic->topic_uuid }}"
            class="card-link"
            ng-init="postCtrl.dwnvoteTally('{{ $topic->topic_uuid }}')"
            ng-click="postCtrl.dwnvote('{{ $topic->topic_uuid }}','{!! $topic->topics_uid !!}')">
                <i class="fa fa-chevron-down"></i>
                    <span id="dwn_cnt_{{$topic->topic_uuid}}">
        {{--                {!! $topic->dwnvote !!}--}}
                        {{ postCtrl.dwnvote_<?= $topic->topic_uuid?> }}
                    </span>
      </a>
      <a href="#" class="card-link" ng-click="postCtrl.commentCount('{{ $topic->topic_uuid }}','{!! $topic->topics_uid !!}')">
          <i class="fa fa-comment-o"></i>
            <span id="coments_cnt_{{$topic->topic_uuid}}">
{{--                {!! $topic->comments !!}--}}
            </span>
      </a>
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