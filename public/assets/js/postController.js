angular.module('App')
    .controller('PostCtrl',function($http,$scope, $sce, $mdDialog, $mdMedia,$firebaseObject,$firebaseArray,Topics){

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



        //Tags that user follow
        postCtrl.userTagList = function(user_uuid)
        {
            var ref = postCtrl.topics.userUrl(user_uuid).child('follow_tag');
            ref.on("value", function(snapshot) {

                $http.post('/getTagButton/', {data: snapshot.val()})
                    .then(function(response){
                        console.log(response.data)
                        $('#userTagList').html(response.data)
                })
                console.log(snapshot.val());
                var data = snapshot.val();
                postCtrl.userTags = snapshot.val();
                //$('#userTagList').html(data)
            })
        }

        //Check the current status on this tag for this user
        postCtrl.followTagStatus = function(user_uuid,tag)
        {
            var followTag = postCtrl.topics.userUrl(user_uuid).child('follow_tag/'+tag);
            followTag.once("value", function(snapshot) {
                if(snapshot.exists()) {
                    postCtrl.tagFollowStatus = 'following';
                }else{
                    postCtrl.tagFollowStatus = 'follow';
                }
            })
        }


        //Follow Tag
        postCtrl.followTag = function(user_uuid,tag)
        {
            var followTag = postCtrl.topics.userUrl(user_uuid).child('follow_tag/'+tag);
            followTag.once("value", function(snapshot) {
                if(snapshot.exists())
                {
                    postCtrl.topics.userUrl(user_uuid).child('follow_tag/' + tag).remove();
                    postCtrl.tagFollowStatus = 'follow';
                }
                else
                {
                    //Add this tag to user's follow tag list
                    postCtrl.topics.userUrl(user_uuid).child('follow_tag/' + tag).set(moment().format());
                    postCtrl.tagFollowStatus = 'following';
                }
            })
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
        postCtrl.followUser = function(user_uuid,uuid)
        {
            var followStatus = postCtrl.topics.userUrl(user_uuid).child('follow_user/'+uuid);
            followStatus.once("value", function(snapshot) {
                if(snapshot.exists() == false)
                {
                    postCtrl.topics.userUrl(user_uuid).child('follow_user/'+uuid).set(moment().format());
                    postCtrl.postFollow = 'following'

                    //Update stat for user being follow
                    var followStatus = postCtrl.topics.userUrl(uuid).child('stat/follower/')
                    followStatus.transaction(function (current_value) {
                        return (current_value || 0) + 1;
                    })
                }
                else
                {
                    postCtrl.topics.userUrl(user_uuid).child('follow_user/'+uuid).remove();
                    postCtrl.postFollow = 'follow'

                    var followStatus = postCtrl.topics.userUrl(uuid).child('stat/follower/')
                    followStatus.transaction(function (current_value) {
                        return (current_value || 0) - 1;
                    })
                }
            })
        }


        //Is currently following user
        //@Params uuid - author ID
        postCtrl.isFollow = function(user_uuid,uuid)
        {
            var followStatus = postCtrl.topics.userUrl(user_uuid).child('follow_user/'+uuid);
            followStatus.once("value", function(snapshot) {
                if(snapshot.exists() == false)
                {
                    postCtrl.postFollow = 'follow'
                }
                else
                {
                    postCtrl.postFollow = 'following'
                }
            })
        }


        postCtrl.getFeedCate = function(slug,catename){
            postCtrl.slug = slug;
            postCtrl.feedName =  catename;
            $http.post('/getFeed/', {slug: slug})
                .then(function(response){
                    postCtrl.feedHtml = response.data;
                    $('#homeFeed').html(response.data);
                    //postCtrl.FeedHtml = $sce.trustAsHtml(response.data);
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


        //Get all messages for reply in reply
        postCtrl.replyInReplyList = function(reply_id)
        {
            var key = '#replyInReply_'+reply_id;
            $http.post('/replyInReplyList', { reply_id: reply_id })
                .then(function(response){
                    $(key).html(response.data);
                })
        }

        //Submit reply in reply
        postCtrl.submitReplyInReply = function(reply_id,topic_uuid,user_uuid)
        {
            var key = '#replyInReplyContainer_'+reply_id;
            $http.post('/replyInReply', {   uuid: user_uuid,
                                            topics_uuid: topic_uuid,
                                            reply_id: reply_id,
                                            data: $(key).html() })
                .then(function(response){
                    postCtrl.replyInReplyList(reply_id);
                })
        }

        //Upvote user in reply in reply
        postCtrl.replyInReplyUpvote = function(reply_id,topic_uuid,recipient,user_uuid)
        {
            postCtrl.replyInReplyDownvoteReset(reply_id,user_uuid);

            var upvoteReplyInReply = postCtrl.topics.ref.child('rir/'+reply_id+'/upvote');

            upvoteReplyInReply.once("value", function(snapshot) {
                if(snapshot.exists() == false)
                {
                    postCtrl.topics.ref.child('rir/'+reply_id+'/upvote/'+'/'+user_uuid)
                        .set({'recipient': recipient ,'created_at':moment().format()});

                    var ttlUpvote = postCtrl.topics.ref.child('rir/'+reply_id+'/upvote_total')
                    ttlUpvote.transaction(function (current_value) {
                        return (current_value || 0) + 1;
                    });


                    var recipientRef = postCtrl.topics.userUrl(recipient).child('stat/upvote');
                    recipientRef.transaction(function (current_value) {
                        return (current_value || 0) + 1;
                    });

                    var recipientRef = postCtrl.topics.userUrl(recipient).child('stat/downvote');
                    recipientRef.transaction(function (current_value) {
                        return negCurrentValueCheck(current_value);
                    });
                }
                else{
                    postCtrl.replyInReplyUpvoteReset(reply_id,user_uuid);

                    var recipientRef = postCtrl.topics.userUrl(recipient).child('stat/upvote');
                    recipientRef.transaction(function (current_value) {
                        return negCurrentValueCheck(current_value);
                    });
                }
            })
        }

        //Upvote user in reply in reply
        postCtrl.replyInReplyDownvote = function(reply_id,topic_uuid,recipient,user_uuid)
        {
            postCtrl.replyInReplyUpvoteReset(reply_id,user_uuid);

            var upvoteReplyInReply = postCtrl.topics.ref.child('rir/'+reply_id+'/downvote');

            upvoteReplyInReply.once("value", function(snapshot) {
                if(snapshot.exists() == false)
                {
                    postCtrl.topics.ref.child('rir/'+reply_id+'/downvote/'+'/'+user_uuid)
                        .set({'recipient': recipient ,'created_at':moment().format()});

                    var ttlUpvote = postCtrl.topics.ref.child('rir/'+reply_id+'/downvote_total')
                    ttlUpvote.transaction(function (current_value) {
                        return (current_value || 0) + 1;
                    });


                    var recipientRef = postCtrl.topics.userUrl(recipient).child('stat/downvote');
                    recipientRef.transaction(function (current_value) {
                        return (current_value || 0) + 1;
                    });

                    var recipientRef = postCtrl.topics.userUrl(recipient).child('stat/upvote');
                    recipientRef.transaction(function (current_value) {
                        return negCurrentValueCheck(current_value);
                    });
                }
                else{

                    postCtrl.replyInReplyDownvoteReset(reply_id,user_uuid);

                    var recipientRef = postCtrl.topics.userUrl(recipient).child('stat/downvote');
                    recipientRef.transaction(function (current_value) {
                        return negCurrentValueCheck(current_value);
                    });
                }
            })
        }


        //Reply In Reply Upvote Tally
        postCtrl.replyInReplyUpvoteTally = function(reply_id)
        {
            var ttlUpvote = postCtrl.topics.ref.child('rir/'+reply_id+'/upvote_total')
            ttlUpvote.on("value",function(snapshot){
                var key = 'reply_upvote_'+reply_id;
                postCtrl[key]  = snapshot.val();
            })
        }

        postCtrl.replyInReplyDownvoteTally = function(reply_id)
        {
            var ttlUpvote = postCtrl.topics.ref.child('rir/'+reply_id+'/downvote_total')
            ttlUpvote.on("value",function(snapshot){
                var key = 'reply_downvote_'+reply_id;
                postCtrl[key]  = snapshot.val();
            })
        }

        //Reset upvote to zero
        postCtrl.replyInReplyUpvoteReset =function(reply_id,user_uuid)
        {
            postCtrl.topics.ref.child('rir/'+reply_id+'/upvote/'+user_uuid).remove();

            var ttlUpvote = postCtrl.topics.ref.child('rir/'+reply_id+'/upvote_total')
            ttlUpvote.transaction(function (current_value) {
                return negCurrentValueCheck(current_value);
            });

        }

        //Reset downvote to zero
        postCtrl.replyInReplyDownvoteReset =function(reply_id,user_uuid)
        {
            postCtrl.topics.ref.child('rir/'+reply_id+'/downvote/'+user_uuid).remove();

            var ttlUpvote = postCtrl.topics.ref.child('rir/'+reply_id+'/downvote_total')
            ttlUpvote.transaction(function (current_value) {
                return negCurrentValueCheck(current_value);
            });
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

        postCtrl.updateTopicContent = function(topic_uuid,topic_id)
        {
            var data = {
                topic_id: topic_id,
                body: $('#topicContent').html(),
                text: $('#topicContent').text()
            }
            $http.post('/updateTopicContent', {data: data })
                .then(function(response){

                })

        }

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


        postCtrl.getPostImage = function(uuid)
        {
            $http.post('/getPostImages', {uuid: uuid })
                .then(function(response) {
                    console.log(response);

                    var key = '#previewImage_'+uuid;
                    $(key).html(response.data);
                })
        }


        //Count the number of bookmark per topic
        postCtrl.bookMarkTally = function(topic_uuid)
        {
            var ref = postCtrl.topics.ref.child('topic/'+topic_uuid+'/bookmark')
            ref.on("value", function (snapshot) {
                var key = 'bookmarks_'+topic_uuid;
                postCtrl[key]  = snapshot.val();
            });
        }

        postCtrl.bookMark = function(user_uuid,topic_uuid)
        {
            var userBookmark = postCtrl.topics.userUrl(user_uuid).child('bookmark/'+topic_uuid);

            userBookmark.once("value", function(snapshot) {
                if(snapshot.exists() == false)
                {
                    postCtrl.topics.userUrl(user_uuid).child('bookmark/'+topic_uuid).set(moment().format());

                    var topicRef = postCtrl.topics.ref.child('topic/' + topic_uuid + '/bookmark')
                    topicRef.transaction(function (current_value) {
                        return (current_value || 0) + 1;
                    });
                }
                else{
                    postCtrl.topics.userUrl(user_uuid).child('bookmark/'+topic_uuid).remove();
                    var topicRef = postCtrl.topics.ref.child('topic/' + topic_uuid + '/bookmark')
                    topicRef.transaction(function (current_value) {
                        if(current_value < 0 || current_value == 0 )
                        {
                            return 0;
                        }else {
                            return (current_value || 0) - 1;
                        }
                    });
                }
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
            var userUpvoteRef = postCtrl.topics.userUrl(topic_uid).child('downvote/'+topic_uuid);
            userUpvoteRef.once("value", function(snapshot) {

                //Chck if user already voted
                if (snapshot.exists() == false) {

                    postCtrl.topics.userUrl(topic_uid).child('downvote/'+topic_uuid).set(moment().format());

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
            postCtrl.topics.userUrl(topic_uid).child('upvote/'+topic_uuid).remove();

            //Decrement the tally
            var topicRef = postCtrl.topics.ref.child('topic/'+topic_uuid+'/upvote')
            topicRef.transaction(function (current_value) {
                return negCurrentValueCheck(current_value);
            });
            
        }

        //Reset downvote to zero
        postCtrl.dwnvoteReset =function(topic_uuid,topic_uid)
        {
            var btn = "#dwnvote_btn_status_"+topic_uuid;
            //Remove voted user
            postCtrl.topics.userUrl(topic_uid).child('downvote/'+topic_uuid).remove();

            //Decrement the tally
            var topicRef = postCtrl.topics.ref.child('topic/'+topic_uuid+'/downvote')
            topicRef.transaction(function (current_value) {
                return negCurrentValueCheck(current_value);
            });
        }


        postCtrl.upvote =function(topic_uuid,topic_uid)
        {
            postCtrl.dwnvoteReset(topic_uuid,topic_uid);
            var btn = "#upvote_btn_status_"+topic_uuid;
            
            //UserUpvote Value
            var userUpvoteRef = postCtrl.topics.userUrl(topic_uid).child('upvote/'+topic_uuid);
            userUpvoteRef.once("value", function(snapshot) {

                //Chck if user already voted
                if (snapshot.exists() == false) {

                    postCtrl.topics.userUrl(topic_uid).child('upvote/'+topic_uuid).set(moment().format());

                    //Topic Upvote tally
                    var topicRef = postCtrl.topics.ref.child('topic/'+topic_uuid+'/upvote')
                    topicRef.transaction(function (current_value) {
                        return (current_value || 0) + 1;
                    });

                    //Update stat for poster
                    var followStatus = postCtrl.topics.userUrl(topic_uid).child('stat/upvote/')
                    followStatus.transaction(function (current_value) {
                        return (current_value || 0) + 1;
                    })

                }else{ //value already exist
                    postCtrl.upvoteReset(topic_uuid,topic_uid);
                    
                    //Update stat for poster
                    var followStatus = postCtrl.topics.userUrl(topic_uid).child('stat/upvote/')
                    followStatus.transaction(function (current_value) {
                        if(current_value < 0 || current_value == 0 )
                        {
                            return 0;
                        }else{
                            return current_value - 1;
                        }
                    })
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

function negCurrentValueCheck(current_value)
{
    if(current_value < 0 || current_value == 0 || current_value == '' || current_value == null)
    {
        return 0;
    }else {
        return (current_value) - 1;
    }
}