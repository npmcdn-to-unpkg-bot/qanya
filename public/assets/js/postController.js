angular.module('App')
    .controller('PostCtrl',function($http,$scope,$mdDialog, $mdMedia,$firebaseObject,$firebaseArray,Topics){

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
              templateUrl: 'http://192.168.0.100:8888/login',
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

            //Search for images in the content
            $("div#contentBody img").each(function(){
                imgIds.push($(this).attr('src'));
            });


            var data = { title:         postCtrl.title,
                         categories:    postCtrl.categories,
                         tags:          postCtrl.topicTags,
                         body:          $('#contentBody').html(),
                         text:          $('#contentBody').text(),
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



        //Login for material
        postCtrl.showMdLogin = function(ev) {
            var useFullScreen = ($mdMedia('sm') || $mdMedia('xs')) && $scope.customFullscreen;
            $mdDialog.show({
                //controller: 'AuthCtrl as authCtrl',
                //templateUrl: 'templates/html/form-login',
                parent: angular.element(document.body),
                targetEvent: ev,
                clickOutsideToClose: true,
                fullscreen: useFullScreen
            })
        }

        postCtrl.commentsTally    =   function(topic_uuid)
        {
            var ref = postCtrl.topics.ref.child('topic/'+topic_uuid+'/comments')
            ref.on("value", function (snapshot) {
                var key = 'comments_'+topic_uuid;
                postCtrl[key]  = snapshot.val();
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