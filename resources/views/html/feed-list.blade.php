 {{-- Feed --}}


<div ng-controller="PostCtrl as postCtrl" class="animated bounce">
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


            <div ng-init="postCtrl.getPostImage('{{$topic->topic_uuid}}')"
                 id="previewImage_<?=$topic->topic_uuid?>">
                @{{postCtrl.previewImage_<?=$topic->topic_uuid?> }}
            </div>
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


        <topic-tally author="'<?=$topic->topics_uid ?>'" topic="'<?= $topic->topic_uuid ?>'"></topic-tally>

        @include('html.topic-tally',['topics_uid' => $topic->topics_uid, 'uuid'       => $topic->topic_uuid])

    </div>
   {{-- end --}}

   </md-card>

   @endforeach
 </div>