function getFeedCate(slug){
    $.get( "/getFeed/", { slug: slug } )
        .done(function( data ) {
            $('#homeFeed').html(data);
        });
}

function ipLogger()
{
    $.getJSON('http://ipinfo.io', function(data){
        return data;
    })
}
//Angular config and modules

var app = angular.module('App', ['ngMaterial','flow','angularMoment','firebase'])

.constant('FirebaseUrl', 'https://qanya.firebaseio.com/')
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
    .controller('PostCtrl',function($http,$mdDialog,$firebaseObject,$firebaseArray,Topics){

        var postCtrl = this;


        postCtrl.topics = Topics;

        postCtrl.displayname ={
            'text' :'',
            'saveBtn':  false,
            'alert':    false
        }

        postCtrl.topicTags      = [];
        postCtrl.postFeedFollow = 'Follow';
        postCtrl.postFollow     = 'Follow';
        postCtrl.topicReply     = '';



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


        postCtrl.feedFollowStatus = function(slug)
        {
            $http.post('/feedFollowStatus/', {data: slug})
                .then(function(response){
                    console.log(response);
                    if(response.data == 0)
                    {
                        postCtrl.postFeedFollow = 'follow';
                    }else{
                        postCtrl.postFeedFollow = 'following';
                    }
                });
        }


        //Follow categories
        postCtrl.followCate = function(slug){

            $http.post('/follow-cate/', {data: slug})
                .then(function(response){
                    console.log(response)
                    if(response.data== 0)
                    {
                        postCtrl.postFeedFollow ='follow';
                    }
                    else
                    {
                        postCtrl.postFeedFollow ='following';
                    }
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


        postCtrl.getFeedCate = function(slug,catename){
            postCtrl.slug = slug;
            postCtrl.feedName =  catename;
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
        postCtrl.postReply = function(uuid,topics_uid,sender)
        {
            var replyObj = 'reply_append_'+uuid;
            $http.post('/replyTopic', {uuid: uuid,
                                       topics_uid: topics_uid,
                                       data: $('#topicReplyContainer').html() })
                .then(function(response){
                    $http.get("http://ipinfo.io")
                        .then(function(response){
                        var geo_data = response
                        $http.post('/ip-logger', {  uuid: uuid,
                            topics_uid: topics_uid,
                            action: 'reply',
                            geoResponse: geo_data
                        })
                    })
                    var commentCounter = postCtrl.topics.ref.child('topic/'+uuid+'/comments')
                    commentCounter.transaction(function (current_value) {
                        return (current_value || 0) + 1;
                    })
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
                    $http.get("http://ipinfo.io")
                        .then(function(response){
                            var geo_data = response
                            $http.post('/ip-logger', {  uuid: uuid,
                                topics_uid: topics_uid,
                                action: 'topic',
                                geoResponse: geo_data
                            })
                        })
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


        postCtrl.incrementView = function(topic_uuid)
        {
            var ref = new Firebase("https://qanya.firebaseio.com/topic/"+topic_uuid+'/view');
            return ref.on("value", function(snapshot) {
                console.log("view: "+snapshot.val());
                if(snapshot.val() ==0)
                {
                    ref.set(1);
                }else
                {
                    ref.set(snapshot.val()+1)
                }
                return snapshot.val();
            });
        }

        postCtrl.upvoteTally    =   function(topic_uuid)
        {
            var ref = postCtrl.topics.ref.child('topic/'+topic_uuid+'/upvote')
            ref.on("value", function (snapshot) {
                var key = 'upvote_'+topic_uuid;
                postCtrl[key]  = snapshot.val();
            });
        }

        postCtrl.dwnvoteTally    =   function(topic_uuid)
        {
            var ref = postCtrl.topics.ref.child('topic/'+topic_uuid+'/downvote')
            ref.on("value", function(snapshot) {
                var the_string = 'dwnvote_'+topic_uuid;
                postCtrl[the_string]  = snapshot.val();
            });
        }

        postCtrl.dwnvote =function(topic_uuid,topic_uid)
        {
            postCtrl.upvoteReset(topic_uuid,topic_uid);
            var btn = "#dwnvote_btn_status_"+topic_uuid;

            //UserDownvote Value
            var userUpvoteRef = postCtrl.topics.upvoteURL(topic_uid).child('downvote/'+topic_uuid);
            userUpvoteRef.once("value", function(snapshot) {

                //Chck if user already voted
                if (snapshot.exists() == false) {

                    postCtrl.topics.upvoteURL(topic_uid).child('downvote/'+topic_uuid).set(moment().format());

                    //Topic Upvote tally
                    var topicRef = postCtrl.topics.ref.child('topic/'+topic_uuid+'/downvote')
                    topicRef.transaction(function (current_value) {
                        return (current_value || 0) + 1;
                    });
                }else{ //value already exist
                    postCtrl.dwnvoteReset(topic_uuid,topic_uid);
                }
            })
        }


        //Reset upvote to zero
        postCtrl.upvoteReset =function(topic_uuid,topic_uid)
        {
            var btn = "#upvote_btn_status_"+topic_uuid;
            //Remove voted user
            postCtrl.topics.upvoteURL(topic_uid).child('upvote/'+topic_uuid).remove();

            //Decrement the tally
            var topicRef = postCtrl.topics.ref.child('topic/'+topic_uuid+'/upvote')
            topicRef.transaction(function (current_value) {
                if(current_value < 0 || current_value == 0 )
                {
                    return 0;
                }else{
                    return current_value - 1;
                }
            });
            
        }

        //Reset downvote to zero
        postCtrl.dwnvoteReset =function(topic_uuid,topic_uid)
        {
            var btn = "#dwnvote_btn_status_"+topic_uuid;
            //Remove voted user
            postCtrl.topics.upvoteURL(topic_uid).child('downvote/'+topic_uuid).remove();

            //Decrement the tally
            var topicRef = postCtrl.topics.ref.child('topic/'+topic_uuid+'/downvote')
            topicRef.transaction(function (current_value) {
                if(current_value < 0 || current_value == 0 )
                {
                    return 0;
                }else{
                    return current_value - 1;
                }
            });
        }


        postCtrl.upvote =function(topic_uuid,topic_uid)
        {
            postCtrl.dwnvoteReset(topic_uuid,topic_uid);
            var btn = "#upvote_btn_status_"+topic_uuid;
            //UserUpvote Value
            var userUpvoteRef = postCtrl.topics.upvoteURL(topic_uid).child('upvote/'+topic_uuid);
            userUpvoteRef.once("value", function(snapshot) {

                //Chck if user already voted
                if (snapshot.exists() == false) {

                    postCtrl.topics.upvoteURL(topic_uid).child('upvote/'+topic_uuid).set(moment().format());

                    //Topic Upvote tally
                    var topicRef = postCtrl.topics.ref.child('topic/'+topic_uuid+'/upvote')
                    topicRef.transaction(function (current_value) {
                        return (current_value || 0) + 1;
                    });
                }else{ //value already exist
                    postCtrl.upvoteReset(topic_uuid,topic_uid);
                }
            })

            /*$http.post('/upvote/', {    topics_uuid:    topic_uuid,
                                        topic_uid:      topic_uid
                                    })
            .then(function(response){
                console.log(response.data);
                var btn = "#upvote_btn_status_"+topic_uuid;
                if(response.data ==1) {
                    $(btn).addClass("label label-pill label-success");
                }else{
                    $(btn).removeClass("label label-pill label-success");
                }
            });*/
        }
    })
angular.module('App')
    // Topic list
    .factory('Topics', function ($firebaseObject, $firebaseArray, FirebaseUrl) {
        var ref = new Firebase(FirebaseUrl)
        var topics = $firebaseObject(ref)
        var topicsArr = $firebaseArray(ref)
        var topicKey = '';

        var Topics = {

            // Reply listing
            upvoteURL: function (user_uuid){
                return ref.child('user/'+user_uuid)
            },
            replyList: function (topic_uuid) {
                var data = ref.child(topic_uuid + '/replies');
                console.log(data);
                return $firebaseArray(data)
            },
            ref: ref
        }
        return Topics;

    })

angular.module('App')
    .controller('ProfileCtrl',function($http,$mdToast,$timeout, $mdSidenav, $log){

        var profileCtrl = this;

        profileCtrl.profileDescription  =   '';
        profileCtrl.notificationList    =   '';
        profileCtrl.unreadNotification  =   0;


        profileCtrl.toggleRight = buildToggler('alertSideNav');
        profileCtrl.isOpenRight = function(){
            return $mdSidenav('alertSideNav').isOpen();
        };


        var last = {
            bottom: false,
            top: true,
            left: false,
            right: true
        };

        profileCtrl.toastPosition = angular.extend({},last);
        profileCtrl.getToastPosition = function() {
            sanitizePosition();
            return Object.keys(profileCtrl.toastPosition)
                .filter(function(pos) { return profileCtrl.toastPosition[pos]; })
                .join(' ');
        };
        function sanitizePosition() {
            var current = profileCtrl.toastPosition;
            if ( current.bottom && last.top ) current.top = false;
            if ( current.top && last.bottom ) current.bottom = false;
            if ( current.right && last.left ) current.left = false;
            if ( current.left && last.right ) current.right = false;
            last = angular.extend({},current);
        }

        profileCtrl.profileImage = function(files)
        {
            angular.forEach(files, function(flowFile, i){
                console.log(flowFile);
                var fileReader = new FileReader();
                fileReader.onload = function (event) {
                    var uri = event.target.result;
                    //profileCtrl.imageStrings[i] = uri;
                    $.post( "/api/previewImage/", { data: uri} )
                        .done(function( response ) {
                            $http.post( "/upload-profileImage", { img: response} )
                                .then(function( response ) {
                                    $('#profilePhoto').attr( "src", response.data);
                                    $mdToast.show(
                                        $mdToast.simple()
                                            .textContent('Save')
                                            .position(profileCtrl.getToastPosition())
                                            .hideDelay(3000)
                                    );
                                });
                        });
                };
                fileReader.readAsDataURL(flowFile.file);
            });
        }

        //List out notification
        profileCtrl.listNotification = function()
        {
            $http.post('/list-notification')
                .then(function(response){
                    console.log(response);
                    profileCtrl.notificationList = response.data;
                });
        }

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
                            .position('top right')
                            .hideDelay(3000)
                    );
                });
        }

        //Toggle sidebar
        function buildToggler(navID) {
            return function() {
                $mdSidenav(navID)
                    .toggle()
                    .then(function () {
                        $log.debug("toggle " + navID + " is done");
                    });
            }
        }
    });



//# sourceMappingURL=all.js.map
