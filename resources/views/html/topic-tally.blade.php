{{-- Upvote/Downvote/Comments --}}
<div>

    <a href="#" id="upvote_btn_status_{{ $uuid }}"
       class="card-link"
       ng-init="postCtrl.upvoteTally('{{ $uuid }}')"
       @if(Auth::guest())
           ng-click="postCtrl.showMdLogin($event)"
       @else
            ng-click="postCtrl.upvote('{{ $uuid }}','{!! $topics_uid !!}')
        @endif
        ">
        <i class="fa fa-chevron-up"></i>
        <span id="upv_cnt_{{$uuid}}">

            {{ postCtrl.upvote_<?= $uuid?> }}
        </span>
    </a>

    <a  href="#" id="dwnvote_btn_status_{{ $uuid }}"
        class="card-link"
        ng-init="postCtrl.dwnvoteTally('{{ $uuid }}')"
        @if(Auth::guest())
            ng-click="postCtrl.showMdLogin($event)"
        @else
            ng-click="postCtrl.dwnvote('{{ $uuid }}','{!! $topics_uid !!}')
        @endif
        ">
        <i class="fa fa-chevron-down"></i>
        <span id="dwn_cnt_{{$uuid}}">
            {{ postCtrl.dwnvote_<?= $uuid ?> }}
        </span>
    </a>

    <a  href="#"
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
</div>