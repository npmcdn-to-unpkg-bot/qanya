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
        <md-card-header id="post_content_{{$topic->topic_uuid}}">

            {{-- Determine the header for the topic --}}
            <span ng-if="{{ $topic->topic_type }} == 1">
                @{{ 'KEY_STORY' | translate }}
            </span>

            <span ng-if="{{ $topic->topic_type }} == 2;"
                  ng-init="postCtrl.getReview('{{$topic->topic_uuid}}')">
                @{{ 'KEY_REVIEW' | translate }}
            </span>

            <span ng-if="{{ $topic->topic_type }} == 3">
                @{{ 'KEY_QUESTION' | translate }}
            </span>

            &nbsp;
            @{{ 'KEY_IN ' | translate }}
            &nbsp;

           <a href="/channel/{{ $topic->cate_slug }}">
               {{ $topic->cate_name }}
           </a>

           <span flex=""></span>


            <md-button
                    aria-label="bookmark"
                    class="md-icon-button green-font"
                    ng-init="postCtrl.bookMarkTally('{{$topic->topic_uuid}}');
                            @if(Auth::user())
                                postCtrl.userBookMarked('{{Auth::user()->uuid}}', '{{$topic->topic_uuid}}')
                            @endif
                            "
                    @if(Auth::guest())
                        ng-click="postCtrl.showMdLogin($event)"
                    @else
                        ng-click="postCtrl.bookMark('{{Auth::user()->uuid}}', '{{$topic->topic_uuid}}')"
                    @endif>
                <h5 id="coments_cnt_{{$topic->topic_uuid}}">
                    <i ng-class="postCtrl.user_bookmarked_<?= $topic->topic_uuid?>? 'fa fa-bookmark fa-1x' : 'fa fa-bookmark-o fa-1x'"></i>
                </h5>

            </md-button>
        </md-card-header>


        {{-- Author part --}}
        <md-card-header ng-init="profileCtrl.getUserStat('{{$topic->topics_uid}}')">
            <md-card-avatar>
                <img class="md-user-avatar" src="{{$topic->profile_img}}"/>
            </md-card-avatar>
            <md-card-header-text>
                <span class="md-title md-padding">
                     <a href="/{{ $topic->displayname }}">
                        {{ $topic->firstname }}
                     </a>
                     {{ $topic->displayname }} -
                    <span am-time-ago="'{!! $topic->topic_created_at !!}' | amParse:'YYYY-MM-DD H:i:s'"></span>

                    <p class="md-subhead">
                        <b> {{ profileCtrl.user_stat_<?=str_replace('-','',$topic->topics_uid)?>.upvote }}</b>
                        @{{ 'KEY_UPVOTE' | translate }}

                        <b> {{ profileCtrl.user_stat_<?=str_replace('-','',$topic->topics_uid)?>.upvote }}</b>
                        @{{ 'KEY_UPVOTE' | translate }}

                      {{--{!! HTML::decode(trim($topic->description)) !!}--}}
                    </p>
                </span>
            </md-card-header-text>
        </md-card-header>
        {{-- End Author --}}


        <div class="card-block">

            <span ng-if="{{ $topic->topic_type }} == 2;" class="pull-right">
                <review-topic data="postCtrl.responseReview<?=$topic->topic_uuid?>"></review-topic>
            </span>

            <h4 class="card-title">
                <a href="{{ url($topic->displayname.'/'.$topic->topic_slug) }}" target="_blank" class="md-title">
                 {!! HTML::decode($topic->topic) !!}
                </a>
            </h4>

            <div class="card-text">

                {{-- body --}}
                <div class="md-body-1">
                    {!! clean(str_limit(nl2br($topic->text),250)) !!}
                </div>

                {{-- Preview images --}}
                <div ng-if="{{$topic->num_img}} > 0">
                    <div ng-init="postCtrl.getPostImage('{{$topic->topic_uuid}}')"
                         id="previewImage_<?=$topic->topic_uuid?>">
                        <preview-images data="postCtrl.previewImage_{{$topic->topic_uuid}}"></preview-images>

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

            @include('html.topic-tally',['topics_uid' => $topic->topics_uid, 'uuid' => $topic->topic_uuid])

        </div>
    {{-- end --}}

    </md-card>

   @endforeach
 </div>