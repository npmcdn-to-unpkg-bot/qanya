@extends('layouts.app')

@section('content')

    <div class="row" ng-controller="ProfileCtrl as profileCtrl">
        <div class="container-fluid">
            <div class="layoutSingleColumn">
                <div class="row">
                    <div class="col-xs-4">
                        <img src="{{ $user->profile_img}}"
                             id="profilePhoto"
                             class="img-fluid img-circle"
                             width="150px">

                            @if($is_user == 'TRUE')
                                <div flow-init
                                     flow-name="uploader.flow"
                                     flow-files-added="profileCtrl.profileImage($files)">
                                    <md-button flow-btn type="file" name="image">
                                        Upload photo
                                    </md-button>
                                </div>
                            @endif
                    </div>

                    <div class="col-xs-8">
                        <h2 class="lead">
                            <strong>
                            {{ $user->firstname }}
                            {{ $user->lastname }}
                            </strong>
                            <p>
                                <small>
                                    {{ $user->displayname }}
                                </small>
                            </p>
                        </h2>
                        <div contenteditable="{{ $is_user }}"
                             class="md-subhead"
                             id= "profileDescription"
                             ng-blur    =   "profileCtrl.updateDescription()"
                             placeholder=   "write your status/description">
                            {{ $user->description }}
                        </div>
                        <div class="row">
                            <h5 class="col-xs-4" id="post_{!! $user->uuid !!}">
                                {!! $user->posts !!}
                                <small class="text-muted">posts</small>
                            </h5>
                            <h5 class="col-xs-4" id="follower_{!! $user->uuid !!}">
                                {!! $user->followers !!}
                                <small class="text-muted">follower</small>
                            </h5>
                            <h5 class="col-xs-4" id="following_{!! $user->uuid !!}">
                                {!! $user->following !!}
                                <small class="text-muted">following</small>
                            </h5>
                        </div>
                    </div>
                </div>

                <div ng-cloak>
                    <md-content>
                        <md-tabs md-dynamic-height md-border-bottom>
                            <md-tab label="topics created">
                                <md-content class="md-padding">
                                    @include('html.feed-list',compact('topics'));
                                </md-content>
                            </md-tab>
                            <md-tab label="replies">
                                <md-content class="md-padding">
                                    <h1 class="md-display-2">Tab Two</h1>
                                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla venenatis ante augue. Phasellus volutpat neque ac dui mattis vulputate. Etiam consequat aliquam cursus. In sodales pretium ultrices. Maecenas lectus est, sollicitudin consectetur felis nec, feugiat ultricies mi. Aliquam erat volutpat. Nam placerat, tortor in ultrices porttitor, orci enim rutrum enim, vel tempor sapien arcu a tellus. Vivamus convallis sodales ante varius gravida. Curabitur a purus vel augue ultrices ultricies id a nisl. Nullam malesuada consequat diam, a facilisis tortor volutpat et. Sed urna dolor, aliquet vitae posuere vulputate, euismod ac lorem. Sed felis risus, pulvinar at interdum quis, vehicula sed odio. Phasellus in enim venenatis, iaculis tortor eu, bibendum ante. Donec ac tellus dictum neque volutpat blandit. Praesent efficitur faucibus risus, ac auctor purus porttitor vitae. Phasellus ornare dui nec orci posuere, nec luctus mauris semper.</p>
                                    <p>Morbi viverra, ante vel aliquet tincidunt, leo dolor pharetra quam, at semper massa orci nec magna. Donec posuere nec sapien sed laoreet. Etiam cursus nunc in condimentum facilisis. Etiam in tempor tortor. Vivamus faucibus egestas enim, at convallis diam pulvinar vel. Cras ac orci eget nisi maximus cursus. Nunc urna libero, viverra sit amet nisl at, hendrerit tempor turpis. Maecenas facilisis convallis mi vel tempor. Nullam vitae nunc leo. Cras sed nisl consectetur, rhoncus sapien sit amet, tempus sapien.</p>
                                    <p>Integer turpis erat, porttitor vitae mi faucibus, laoreet interdum tellus. Curabitur posuere molestie dictum. Morbi eget congue risus, quis rhoncus quam. Suspendisse vitae hendrerit erat, at posuere mi. Cras eu fermentum nunc. Sed id ante eu orci commodo volutpat non ac est. Praesent ligula diam, congue eu enim scelerisque, finibus commodo lectus.</p>
                                </md-content>
                            </md-tab>
                            <md-tab label="photos">
                                <md-content class="md-padding">
                                    <h1 class="md-display-2">Tab Three</h1>
                                    <p>Integer turpis erat, porttitor vitae mi faucibus, laoreet interdum tellus. Curabitur posuere molestie dictum. Morbi eget congue risus, quis rhoncus quam. Suspendisse vitae hendrerit erat, at posuere mi. Cras eu fermentum nunc. Sed id ante eu orci commodo volutpat non ac est. Praesent ligula diam, congue eu enim scelerisque, finibus commodo lectus.</p>
                                </md-content>
                            </md-tab>
                        </md-tabs>
                    </md-content>
                </div>

            </div>
        </div>
    </div>

@endsection