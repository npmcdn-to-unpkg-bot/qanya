 {{-- Feed --}}


<div ng-controller="PostCtrl as postCtrl" class="animated bounce"
     @if(Auth::check())
         {{--if user is login then get their current upvote and downvote --}}
         ng-init="profileCtrl.getUserUpvote('<?php echo Auth::user()->uuid?>');
                  profileCtrl.getUserDwnvote('<?php echo Auth::user()->uuid?>')"
    @endif
    >
  @foreach($topics as $topic)
    <md-card class="row">
        <md-card-header>
            {{-- Determine the header for the topic --}}
            <span ng-if="{{ $topic->topic_type }} == 1">
                Topic
            </span>
            <span ng-if="{{ $topic->topic_type }} == 2;"
                  ng-init="postCtrl.getReview('{{$topic->topic_uuid}}')">
                Review
            </span>
            <span ng-if="{{ $topic->topic_type }} == 3">
                Question
            </span>

            &nbsp;
            in
            &nbsp;

           <a href="/channel/{{ $topic->cate_slug }}">
               {{ $topic->cate_name }}
           </a>

           <span flex=""></span>

            <a  href="#"
                ng-class="card-link"
                ng-init="postCtrl.bookMarkTally('{{$topic->topic_uuid}}')"
                @if(Auth::guest())
                    ng-click="postCtrl.showMdLogin($event)"
                @else
                    ng-click="postCtrl.bookMark('{{Auth::user()->uuid}}', '{{$topic->topic_uuid}}')"
                @endif>
                <h5 id="coments_cnt_{{$topic->topic_uuid}}">
                    {{ postCtrl.bookmarks_<?= $topic->topic_uuid ?> }}
                    <i class="fa fa-bookmark-o fa-1x"></i>
                </h5>

            </a>
        </md-card-header>
        <md-card-header>
            <md-card-avatar>
                <img class="md-user-avatar" src="{{$topic->profile_img}}"/>
            </md-card-avatar>
            <md-card-header-text>
                <span class="md-title">
                 <a href="/{{ $topic->displayname }}">
                    {{ $topic->firstname }}
                 </a>
                 {{ $topic->displayname }} -
                <span am-time-ago="'{!! $topic->topic_created_at !!}' | amParse:'YYYY-MM-DD H:i:s'"></span>

                <p class="md-subhead">
                  {!! HTML::decode(trim($topic->description)) !!}
                </p>
                </span>
            </md-card-header-text>
        </md-card-header>

    <div class="card-block">
  <span ng-if="{{ $topic->topic_type }} == 2;" class="pull-right">
            <review-topic data="postCtrl.responseReview<?=$topic->topic_uuid?>"></review-topic>
        </span>
        <h4 class="card-title">

            <a href="{{ url($topic->displayname.'/'.$topic->topic_slug) }}" target="_blank">
             {!! HTML::decode($topic->topic) !!}
            </a>
        </h4>




        <div class="card-text">


          {!! clean(str_limit(nl2br($topic->text),250)) !!}

            {{-- Preview images --}}
            <div ng-if="{{$topic->num_img}} > 0">
                <div ng-init="postCtrl.getPostImage('{{$topic->topic_uuid}}')"
                     id="previewImage_<?=$topic->topic_uuid?>">
                    @{{postCtrl.previewImage_<?=$topic->topic_uuid?> }}
                </div>
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


        {{--<topic-tally data="'{{ ['topics_uid' => $topic->topics_uid, 'uuid'       => $topic->topic_uuid] }}'">
        </topic-tally>--}}

        @include('html.topic-tally',['topics_uid' => $topic->topics_uid, 'uuid' => $topic->topic_uuid])

    </div>
   {{-- end --}}

   </md-card>

   @endforeach
 </div>