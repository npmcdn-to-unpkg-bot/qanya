{{-- Upvote/Downvote/Comments --}}
<div>

    <span ng-init="postCtrl.upvoteTally('{{ $uuid }}')">
        @if(Auth::user()->uuid != $topics_uid)

        <a href="#tally-container-{{ $uuid }}" id="upvote_btn_status_{{ $uuid }}"

           @if(Auth::guest())
                ng-click="postCtrl.showMdLogin($event)"
                ng-class='card-link'
           @else
                ng-click="postCtrl.upvote('{{ $uuid }}','{!! $topics_uid !!}');
                          profileCtrl.user_dwnvoted_<?php echo $uuid?> = false"
                ng-class="profileCtrl.user_upvoted_<?php echo $uuid?> ? 'card-link label label-success' : 'card-link'"
            @endif
            ">
            <i class="fa fa-chevron-up"></i>

            <span id="upv_cnt_{{$uuid}}">
                {{ postCtrl.upvote_<?= $uuid?> }}
            </span>
        </a>
        @else
            <i class="fa fa-chevron-up"></i>
            <span id="upv_cnt_{{$uuid}}">
                {{ postCtrl.upvote_<?= $uuid?> }}
            </span>
        @endif
    </span>


    <span ng-init="postCtrl.dwnvoteTally('{{ $uuid }}')">
        @if(Auth::user()->uuid != $topics_uid)
            <a  href="#tally-container-{{ $uuid }}" id="dwnvote_btn_status_{{ $uuid }}"

                @if(Auth::guest())
                    ng-click="postCtrl.showMdLogin($event)"
                    ng-class='card-link'
                @else
                    ng-click="postCtrl.dwnvote('{{ $uuid }}','{!! $topics_uid !!}');
                              profileCtrl.user_upvoted_<?php echo $uuid?> = false"
                    ng-class="profileCtrl.user_dwnvoted_<?php echo $uuid?> ? 'card-link label label-success' : 'card-link'"
                @endif
                ">
                <i class="fa fa-chevron-down"></i>
                <span id="dwn_cnt_{{$uuid}}">
                    {{ postCtrl.dwnvote_<?= $uuid ?> }}
                </span>
            </a>

            <a  href="#tally-container-{{ $uuid }}"
                class="card-link"
                ng-init="postCtrl.commentsTally('{{ $uuid }}')"
                @if(Auth::guest())
                    ng-click="postCtrl.showMdLogin($event)"
                @endif>
                <i class="fa fa-comment-o"></i>
                                <span id="coments_cnt_{{$uuid}}">
                                    {{ postCtrl.comments_<?= $uuid ?> }}
                                </span>
            </a>
        @else
                <i class="fa fa-chevron-down"></i>
                <span id="dwn_cnt_{{$uuid}}">
                    {{ postCtrl.dwnvote_<?= $uuid ?> }}
                </span>
        @endif
    </span>


</div>