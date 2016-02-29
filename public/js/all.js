function getFeedCate(slug){
    $.get( "/getFeed/", { slug: slug } )
        .done(function( data ) {
            $('#homeFeed').html(data);
        });
}
//Angular config and modules

var app = angular.module('App', ['ngMaterial','flow','angularMoment'])

.config(["$mdThemingProvider", function ($mdThemingProvider) {
    $mdThemingProvider.definePalette('slack', {
        '50': 'ffebee',
        '100': 'ffcdd2',
        '200': 'ef9a9a',
        '300': 'e57373',
        '400': 'ef5350',
        '500': '4D394B', // primary colour
        '600': 'e53935',
        '700': 'd32f2f',
        '800': 'c62828',
        '900': 'b71c1c',
        'A100': 'ff8a80',
        'A200': 'ff5252',
        'A400': 'ff1744',
        'A700': 'd50000',
        'contrastDefaultColor': 'light', // whether, by default, text (contrast)
        // on this palette should be dark or light
        'contrastDarkColors': ['50', '100', // hues which contrast should be 'dark' by default
            '200', '300', '400', 'A100'],
        'contrastLightColors': undefined // could also specify this if default was 'dark'
    })
    $mdThemingProvider.theme('default')
        .primaryPalette('slack')
}])

angular.module('App')
    .controller('PostCtrl',function($http,$mdDialog){

        var postCtrl = this;

        postCtrl.displayname ={
            'text' :'',
            'saveBtn':  false,
            'alert':    false
        }

        postCtrl.topicTags = [];
        postCtrl.postFollow = 'Follow';
        postCtrl.topicReply = '';


        postCtrl.showLogin = function(ev) {
        var useFullScreen = ($mdMedia('sm') || $mdMedia('xs'))  && $scope.customFullscreen;
            $mdDialog.show({              
              templateUrl: 'dialog1.tmpl.html',
              parent: angular.element(document.body),
              targetEvent: ev,
              clickOutsideToClose:true,
              fullscreen: useFullScreen
            })
        }


        //Follow categories
        postCtrl.followCate = function(slug){

            $http.post('/follow-cate/', {slug: slug})
                .then(function(response){
                    console.log(response)
                });
        }


        //Follower user
        //@Params uuid - author ID
        postCtrl.followUser = function(uuid)
        {
            $http.post('/followUser/', { data: uuid})
                .then(function(response){
                    if(response.data == 0)
                    {
                        postCtrl.postFollow = 'follow';
                    }else{

                        postCtrl.postFollow = 'following';
                    }
                });
        }


        //Is currently following user
        //@Params uuid - author ID
        postCtrl.isFollow = function(uuid)
        {
            $http.post('/userFollowStatus/', { data: uuid})
                .then(function(response){
                    if(response.data == 0)
                    {
                        postCtrl.postFollow = 'follow';
                    }else{
                        postCtrl.postFollow = 'following';
                    }
                });
        }


        postCtrl.getFeedCate = function(slug){
            postCtrl.slug = slug;
            $http.post('/getFeed/', {slug: slug})
                .then(function(response){
                    $('#homeFeed').html(response.data);
                });
        }


        postCtrl.checkDisplayname = function(){

            $http.post('/check-name', {name: postCtrl.displayname.text})
                .then(function(response){
                if(response.data == "0"){
                    postCtrl.displayname.saveBtn = true;
                    postCtrl.displayname.alert   = false;
                }else{
                    postCtrl.displayname.saveBtn = false;
                    postCtrl.displayname.alert   = true;
                }
            });
        }


        //Reply in the post
        postCtrl.postReply = function(uuid,topics_uid)
        {
            var replyObj = 'reply_append_'+uuid;
            $http.post('/replyTopic', {uuid: uuid,
                                       topics_uid: topics_uid,
                                       data: $('#topicReplyContainer').html() })
                .then(function(response){
                    console.log(response);
                })
        }


        //Post topic
        postCtrl.postTopic = function()
        {
            var imgIds = new Array();

            $("div#contentBody img").each(function(){
                imgIds.push($(this).attr('src'));
            });

            var data = { title:         postCtrl.title,
                         categories:    postCtrl.categories,
                         tags:          postCtrl.topicTags,
                         body:          $('#contentBody').html(),
                         images:        imgIds
                        };
            $.post( "/api/postTopic/", { data: data} )
                .done(function( response ) {
                    url = '/'+response.author+'/'+response.slug;
                    window.location = url;
                })

        }

        //Preview images
        postCtrl.imageStrings = [];
        postCtrl.processFiles = function(files){
            angular.forEach(files, function(flowFile, i){
                console.log(flowFile);
                var fileReader = new FileReader();
                fileReader.onload = function (event) {
                    var uri = event.target.result;
                    postCtrl.imageStrings[i] = uri;
                    $.post( "/api/previewImage/", { data: uri} )
                        .done(function( response ) {
                            $('#contentBody').append('<img src=\"'+response+'\" class=\"img-fluid\">');
                        })
                };
                fileReader.readAsDataURL(flowFile.file);
            });

        };
    })
angular.module('App')
    .controller('ProfileCtrl',function($http,$mdToast){

        var profileCtrl = this;

        profileCtrl.profileDescription='';
        profileCtrl.unreadNotification = 0;


        //Acknowledge notification
        profileCtrl.ackNotificataion = function()
        {
            $http.post('/ackNotification')
                .then(function(response){
                    profileCtrl.unreadNotification = response.data;
                });
        }

        //Get the number of unread notificaiton
        profileCtrl.userNotification = function()
        {
            $http.post('/getNotification')
                .then(function(response){
                    profileCtrl.unreadNotification = response.data;
                });
        }

        profileCtrl.updateDescription = function()
        {
            $http.post('/user/update-description',
                {name: $('#profileDescription').html()})
                .then(function(response){

                    $mdToast.show(
                        $mdToast.simple()
                            .textContent('Save!')
                            .position('top')
                            .hideDelay(3000)
                    );
                });
        }
    })
//# sourceMappingURL=all.js.map
