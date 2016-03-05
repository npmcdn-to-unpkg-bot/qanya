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

        postCtrl.replyList =null;
        postCtrl.getReplies = function(uuid)
        {
            console.log(postCtrl.topics.replyList(uuid));
            postCtrl.replyList  = postCtrl.topics.replyList(uuid);

            console.log(postCtrl.replyList);
            /*topicRef.on('value', function(snapshot) {
                var the_string = 'topic_replies_'+uuid;
                postCtrl.replyList = snapshot.val()
            });*/

/*            var obj = $firebaseArray(topicRef);
            console.log(obj);
            postCtrl.replyList = obj.$loaded()
                    .then(function(data) {
                        return data;
                    })
                    .catch(function(error) {
                        console.error("Error:", error);
                    });*/

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
            var btn = "#dwnvote_btn_status_"+topic_uuid;

            //Reset value of upvote to zero first when downvote select
            postCtrl.upvoteReset(topic_uuid,topic_uid);
            var topicRef = postCtrl.topics.upvoteURL(topic_uid).child('downvote/'+topic_uuid);
            topicRef.once("value", function(snapshot) {
                if (snapshot.exists() == false) {
                    postCtrl.topics.upvoteURL(topic_uid).child('downvote/'+topic_uuid).set(moment().format());
                }else{
                    postCtrl.dwnvoteReset(topic_uuid,topic_uid);
                }
                $(btn).addClass("label label-pill label-success");
            })
            var topicRef = postCtrl.topics.ref.child('topic/'+topic_uuid+'/downvote')
            topicRef.once("value", function (snapshot) {
                topicRef.set(snapshot.val() + 1);
            });
        }


        //Reset upvote to zero
        postCtrl.upvoteReset =function(topic_uuid,topic_uid)
        {
            var btn = "#upvote_btn_status_"+topic_uuid;
            postCtrl.topics.upvoteURL(topic_uid).child('upvote/'+topic_uuid).remove();

            var topicRef = postCtrl.topics.ref.child('topic/'+topic_uuid+'/upvote')
            topicRef.once("value", function (snapshot) {
                var val = snapshot.val();
                topicRef.set(snapshot.val() - 1);
                $(btn).removeClass("label label-pill label-success");
            });
        }

        //Reset downvote to zero
        postCtrl.dwnvoteReset =function(topic_uuid,topic_uid)
        {
            var btn = "#dwnvote_btn_status_"+topic_uuid;
            postCtrl.topics.upvoteURL(topic_uid).child('downvote/'+topic_uuid).remove();

            var topicRef = postCtrl.topics.ref.child('topic/'+topic_uuid+'/downvote')
            topicRef.once("value", function (snapshot) {
                var val = snapshot.val();
                if(val < 0){
                    topicRef.set(0);
                }else{
                    topicRef.set(val - 1);
                }
                $(btn).removeClass("label label-pill label-success");
            });
        }


        postCtrl.upvote =function(topic_uuid,topic_uid)
        {

            var btn = "#upvote_btn_status_"+topic_uuid;

            //Reset value of downvote to zero first when upvote select
            postCtrl.dwnvoteReset(topic_uuid,topic_uid);
            var topicRef = postCtrl.topics.upvoteURL(topic_uid).child('upvote/'+topic_uuid);
            topicRef.once("value", function(snapshot) {
                console.log(snapshot.val())
                console.log(snapshot.exists())
                if (snapshot.exists() == false) {
                    postCtrl.topics.upvoteURL(topic_uid).child('upvote/'+topic_uuid).set(moment().format());
                }else{
                    postCtrl.upvoteReset(topic_uuid,topic_uid);
                }
                $(btn).addClass("label label-pill label-success");
            })
            var topicRef = postCtrl.topics.ref.child('topic/'+topic_uuid+'/upvote')
            topicRef.once("value", function (snapshot) {
                topicRef.set(snapshot.val() + 1);
            });
            /*var ref   = postCtrl.topics.ref.child('user/'+topic_uid+'/upvote/'+topic_uuid);

            ref.once("value", function(snapshot) {

                if(snapshot.exists() == false)
                {
                    //postCtrl.dwnvoteReset(topic_uuid,topic_uid);
                    ref.set({upvote: moment().format() });
                    topicRef.once("value", function(snapshot) {
                        var data = snapshot.val();

                        if($.isEmptyObject(data.upvote_cnt)) //if nothing there
                        {
                            topicRef.child("upvote").set(1);
                        }else{
                            var upvoteVal = data.upvote_cnt
                            topicRef.child("upvote").set(upvoteVal+1);
                        }
                    });

                    $(btn).addClass("label label-pill label-success");
                }else{
                   postCtrl.upvoteReset(topic_uuid,topic_uid);
                }
            });*/

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