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

$('a.card-link').click(function(e)
{
    // Cancel the default action
    e.preventDefault();
});
//Angular config and modules

var app = angular.module('App', ['ngMaterial','flow','angularMoment','firebase',
                                 'toastr',
                                 'angular.filter',
                                 'ngCookies','ngSanitize',
                                 'pascalprecht.translate'])

.constant('FirebaseUrl', 'https://qanya.firebaseio.com/')
.config(["$mdThemingProvider", function ($mdThemingProvider) {
    $mdThemingProvider.definePalette('slack', {
        '50': '5DB09D',
        '100': 'ffcdd2',
        '200': 'ef9a9a',
        '300': 'e57373',
        '400': '5DB09D',
        '500': '684666', // primary colour
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


//For removing that <p> from topic header
.filter('htmlToPlaintext', function() {
        return function(text) {
            return  text ? String(text).replace(/<[^>]+>/gm, '') : '';
        };
    }
)

//Limit the number of length
//http://jsfiddle.net/tuyyx/
.filter('truncate', function () {
    return function (text, length, end) {
        if (isNaN(length))
            length = 10;

        if (end === undefined)
            end = "...";

        if (text.length <= length || text.length - end.length <= length) {
            return text;
        }
        else {
            return String(text).substring(0, length-end.length) + end;
        }

    };
});
angular.module('App')
    .controller('PostCtrl',function($http,$scope, $sce, $mdDialog, $mdMedia,$firebaseObject,$firebaseArray,Topics,toastr){

        var postCtrl = this;


        postCtrl.topics = Topics;

        postCtrl.displayname ={
            'text' :'',
            'saveBtn':  false,
            'alert':    false
        }

        postCtrl.topicTags      =   [];
        postCtrl.postFeedFollow =   'Follow';
        postCtrl.postFollow     =   'Follow';
        postCtrl.topicReply     =   '';

        //Review criteria
        postCtrl.criteria       =   false;
        postCtrl.criteriaReply  =   null;

        postCtrl.reviewCriteria =   false;
        postCtrl.critReplyData  =   null;
        postCtrl.userRateReview =   null;

        //Material Open Menu
        postCtrl.openMenu = function($mdOpenMenu, ev) {
            originatorEv = ev;
            $mdOpenMenu(ev);
        };

        //--- REVIEW ---
        //Get Review from the post
        postCtrl.getReview = function(topic_uuid) {
            $http.post('/retrieve-review/', {data: topic_uuid})
                .then(function (response) {
                    var key = 'responseReview' + topic_uuid;
                    postCtrl[key] = response.data;
                })
        }


        //Calculate the average score
        postCtrl.avgScore = function(scores_arr)
        {
            var ttl_score   =   0;
            var ttl_length  =   0;

            angular.forEach(scores_arr, function(value, key){
                ttl_score = ttl_score+parseInt(value.scores);
                ttl_length++;
            });
            return ttl_score/ttl_length;
        }


        postCtrl.getReviewForm = function(topic_uuid){
            $http.post('/reviewForm/', {data: topic_uuid})
                .then(function (response) {
                    var key = 'responseReview' + topic_uuid;
                    postCtrl[key] = response.data;
                })
        }

        //--- END REVIEW ---


        //Display pop up login
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
            ref.once("value", function(snapshot) {
                postCtrl.userTags = snapshot.val();
                /*$http.post('/getTagButton/', {data: snapshot.val()})
                    .then(function(response){
                        postCtrl.userTags = response.data;
                })*/

                //postCtrl.userTags = snapshot.val();
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
                        return negCurrentValueCheck(current_value);
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
            console.log(postCtrl.responseReview+uuid);

          /*  $http.post('/replyTopic', {uuid: uuid,
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
                })*/
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

        postCtrl.showConfirmDelete = function(ev,topic_uuid,user_uuid) {
            // Appending dialog to document.body to cover sidenav in docs app
            var confirm = $mdDialog.confirm()
                .title('Delete this?')
                .textContent('If you remove this, you will not be able to get it back')
                .ariaLabel('Delete')
                .targetEvent(ev)
                .ok('Please do it!')
                .cancel('nah...');
            $mdDialog.show(confirm).then(function() {
                var data = {
                    topic_uuid: topic_uuid,
                    user_uuid:  user_uuid
                }
                $http.post('/removeTopic', {data: data })
                    .then(function(response){
                        window.location = '/';
                    })
                $scope.status = 'You decided to get rid of your debt.';

            }, function() {
                $scope.status = 'You decided to keep your debt.';
            });
        };



        //For Review
        //Add new item
        postCtrl.addNewChoice = function() {
            var newItemNo = postCtrl.reviewCriteria.length+1;
            postCtrl.reviewCriteria.push({'id':'criteria'+newItemNo});
        };

        //Remove added item
        postCtrl.removeChoice = function() {
            var lastItem = postCtrl.reviewCriteria.length-1;
            postCtrl.reviewCriteria.splice(lastItem);
        };


        //Post topic
        postCtrl.postTopic = function()
        {
            var imgIds = new Array();

            //Search for images in the content
            $("div#contentBody img").each(function(){
                imgIds.push($(this).attr('src'));
            });


            var data = { title:         postCtrl.title,
                         type:          postCtrl.postTypes,
                         categories:    postCtrl.categories,
                         tags:          postCtrl.topicTags,
                         body:          $('#contentBody').html(),
                         text:          $('#contentBody').text(),
                         images:        imgIds,
                         reviews:       postCtrl.reviewCriteria
                        };
            $.post( "/api/postTopic/", { data: data} )
                .done(function( response ) {
                    console.log(response.data);
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
        postCtrl.processFiles = function(files,container){
            angular.forEach(files, function(flowFile, i){
                var fileReader = new FileReader();
                fileReader.onload = function (event) {
                    var uri = event.target.result;
                    toastr.info('uploading...!');
                    postCtrl.imageStrings[i] = uri;
                    $.post( "/api/previewImage/", { data: uri} )
                        .done(function( response ) {
                            toastr.success('Done!',{
                                iconClass: 'toast-success'
                            });
                            $(container).append('<img src=\"'+response+'\" class=\"img-fluid\">');
                        })
                };
                fileReader.readAsDataURL(flowFile.file);
            });
        };


        //Update topic content
        postCtrl.updateTopicContent = function(topic_uuid,topic_id)
        {
            var imgIds = new Array();
            //Search for images in the content
            $("div#topicContent img").each(function(){
                imgIds.push($(this).attr('src'));
            });

            var data = {
                topic_uuid: topic_uuid,
                topic_id:   topic_id,
                body:       $('#topicContent').html(),
                text:       $('#topicContent').text(),
                images:     imgIds
            }
            $http.post('/api/updateTopicContent', {data: data })
                .then(function(response){
                    toastr.success('Save!',{
                        iconClass: 'toast-success'
                    });
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

        //Get the post images
        postCtrl.getPostImage = function(uuid)
        {
            $http.post('/getPostImages', {uuid: uuid })
                .then(function(response) {
                    var key = 'previewImage_'+uuid;
                    postCtrl[key] = response.data;
                    /*$(key).html(response.data);
                    postCtrl[key] = response.data;*/
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


        postCtrl.userBookMarked = function(user_uuid,topic_uuid)
        {
            var key = 'user_bookmarked_'+topic_uuid;
            var userBookmark = postCtrl.topics.userUrl(user_uuid).child('bookmark/'+topic_uuid);

            userBookmark.once("value", function(snapshot) {
                if (snapshot.exists() == false) {
                    postCtrl[key] = false;
                }
                else {
                    postCtrl[key] = true;
                }
            })
        }

        postCtrl.bookMark = function(user_uuid,topic_uuid)
        {
            var userBookmark = postCtrl.topics.userUrl(user_uuid).child('bookmark/'+topic_uuid);
            var key = 'user_bookmarked_'+topic_uuid;

            userBookmark.once("value", function(snapshot) {
                if(snapshot.exists() == false)
                {
                    postCtrl.topics.userUrl(user_uuid).child('bookmark/'+topic_uuid).set(moment().format());

                    var topicRef = postCtrl.topics.ref.child('topic/' + topic_uuid + '/bookmark')
                    topicRef.transaction(function (current_value) {
                        return (current_value || 0) + 1;
                    });
                    postCtrl[key]  = true;
                }
                else{
                    postCtrl.topics.userUrl(user_uuid).child('bookmark/'+topic_uuid).remove();
                    
                    var topicRef = postCtrl.topics.ref.child('topic/' + topic_uuid + '/bookmark')
                    topicRef.transaction(function (current_value) {
                        return negCurrentValueCheck(current_value);
                    });
                    postCtrl[key]  = false;
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


        /*
        *  Upvote Downvote topics fropm here
        * */
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
angular.module('App')
    // Topic list
    .factory('Topics', function ($firebaseObject, $firebaseArray, FirebaseUrl) {
        var ref = new Firebase(FirebaseUrl)
        var topics = $firebaseObject(ref)
        var topicsArr = $firebaseArray(ref)
        var topicKey = '';

        var Topics = {

            // Reply listing
            userUrl: function (user_uuid){
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
    .controller('ProfileCtrl',function($http,$mdToast,$timeout, $mdSidenav, $log, $cookies, $translate,
                                       toastr,Topics) {

        var profileCtrl = this;

        profileCtrl.topic = Topics;
        profileCtrl.profileDescription = '';
        profileCtrl.notificationList = '';
        profileCtrl.unreadNotification = 0;
        profileCtrl.userBookmark = 0;
        profileCtrl.userPostedPhotos = '';
        profileCtrl.user = null;




        profileCtrl.toggleRight     = buildToggler('alertSideNav');
        profileCtrl.toggleMobile    = buildToggler('mobile');
        profileCtrl.isOpenRight = function () {
            return $mdSidenav('alertSideNav').isOpen();
        };



        //User profile
        profileCtrl.profile = function(authData){
            profileCtrl.user = JSON.parse(authData);
        }

        //Change language
        profileCtrl.toggleLang = function (langKey) {
            $translate.use(langKey);
            // Setting a cookie
            $cookies.put('userLang', langKey);
            //If user registered - update this in their preference
            /*if(Auth.ref.getAuth()){
                profileCtrl.users.userArrRef(Auth.ref.getAuth().uid).update({"lang":langKey})
            }*/
        }

        //Checkk user selected language
        if(!profileCtrl.profile.lang){
            if($cookies.get('userLang')){
                profileCtrl.toggleLang($cookies.get('userLang'));
            }else{
                profileCtrl.toggleLang('Eng');
            }
        }
        else{
            profileCtrl.toggleLang(profileCtrl.profile.lang);
        }



        //Get user posted photos
        profileCtrl.postedPhotos = function (user_uuid) {
            $http.post('/getPostedPhotos', {data: user_uuid})
                .then(function (response) {
                    profileCtrl.userPostedPhotos = response.data;
                })
        }


        profileCtrl.getUserUpvote = function (user_uuid)
        {
            var ref = profileCtrl.topic.userUrl(user_uuid).child('upvote');
            ref.on("value",function(snapshot){
                snapshot.forEach(function(data) {
                    var key = 'user_upvoted_'+data.key();
                    profileCtrl[key]  = true;
                });
            })
        }

        profileCtrl.getUserDwnvote = function (user_uuid)
        {
            var ref = profileCtrl.topic.userUrl(user_uuid).child('downvote');
            ref.on("value",function(snapshot){

                snapshot.forEach(function(data) {
                    var key = 'user_dwnvoted_'+data.key();
                    profileCtrl[key]  = true;
                });
            })
        }

        //Getting user stat from firebase
        profileCtrl.getUserStat = function(uuid)
        {
            var ref = new Firebase("https://qanya.firebaseio.com/user/"+uuid+"/stat/");
            ref.on("value", function(snapshot) {
                //need to replace '-' since NG doesn't allow us (weird)
                uuid = uuid.replace(/-/g,"");
                var key = 'user_stat_'+uuid;
                profileCtrl[key]  = snapshot.val();
            })
        }


        profileCtrl.profileImage = function(files)
        {
            angular.forEach(files, function(flowFile, i){
                var fileReader = new FileReader();
                fileReader.onload = function (event) {
                    var uri = event.target.result;
                    toastr.info('Saving...!');
                    //profileCtrl.imageStrings[i] = uri;
                    $.post( "/api/previewImage/", { data: uri} )
                        .done(function( response ) {
                            $http.post( "/upload-profileImage", { img: response} )
                                .then(function( response ) {
                                    $('#profilePhoto').attr( "src", response.data);
                                    toastr.success('Save!');
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
                    toastr.success('Save!');
                });
        }

        profileCtrl.getUserBookmark = function(user_uuid)
        {
            var ref = new Firebase("https://qanya.firebaseio.com/user/"+user_uuid+"/bookmark");
            ref.on("value",function (snapshot) {

                $http.post('/user/getBookmark',
                    {data: snapshot.val()})
                    .then(function(response){
                        console.log(response);
                        profileCtrl.userBookmark = response.data;
                    })

                //profileCtrl.userBookmark = snapshot.val();
            });
        }

        profileCtrl.getUserHistory = function(user_uuid)
        {
            var ref = new Firebase("https://qanya.firebaseio.com/user/"+user_uuid+"/history");
            ref.orderByValue().on("value",function (snapshot) {

                console.log(snapshot.val());
                $http.post('/user/getHistory',
                    {data: snapshot.val()})
                    .then(function(response){
                        profileCtrl.userHistory = response.data;
                    })
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



angular.module('App')
.directive('topicTally', function () {
    return {
        //controller: 'TopicCtrl as topicCtrl',
        restrict: 'EA',
        transclude:   true,
        templateUrl: '/assets/templates/topic-tally.html',
        scope: {
            data: '='
        }
    }
})

.directive('profileBadge', function () {
    return {
        controller: 'ProfileCtrl as profileCtrl',
        restrict: 'E',
        transclude:   true,
        scope: {
            data: '='
        },
        templateUrl: '/assets/templates/profile-badge.html'
    }
})

.directive('postedPhotos', function () {
    return {
        controller: 'ProfileCtrl as profileCtrl',
        restrict: 'EA',
        transclude:   true,
        templateUrl: '/assets/templates/posted-photos.html',
        scope: {
            data: '='
        }
    }
})


//Preview images
.directive('previewImages', function () {
    return {
        controller: 'PostCtrl as postCtrl',
        restrict: 'E',
        transclude: true,
        scope: {
            data: '='
        },
        templateUrl: '/assets/templates/preview-images.html'
    }
})

.directive('reviewTopic', function () {
    return {
        controller: 'PostCtrl as postCtrl',
        restrict: 'E',
        transclude: true,
        scope: {
            data: '='
        },
        templateUrl: '/assets/templates/review-topic.html'
    }
})

//Review score form
.directive('reviewForm', function () {
    return {
        controller: 'PostCtrl as postCtrl',
        restrict: 'E',
        transclude: true,
        scope: {
            data: '='
        },
        templateUrl: '/assets/templates/review-form.html'
    }
})


//Review score form
.directive('minFeedList', function () {
    return {
        controller: 'PostCtrl as postCtrl',
        restrict: 'E',
        transclude: true,
        scope: {
            data: '='
        },
        templateUrl: '/assets/templates/min-feed-list.html'
    }
})

//Review score form
.directive('feedList', function () {
    return {
        controller: 'PostCtrl as postCtrl',
        restrict: 'E',
        transclude: true,
        scope: {
            data: '='
        },
        templateUrl: '/assets/templates/feed-list.html'
    }
})


//User Tag list
.directive('userTags', function () {
    return {
        controller: 'PostCtrl as postCtrl',
        restrict: 'E',
        transclude: true,
        scope: {
            data: '='
        },
        templateUrl: '/assets/templates/tag-list.html'
    }
})
angular.module('App')
    .config(['$translateProvider', function ($translateProvider) {
        $translateProvider.translations('Eng', {
            'KEY_LOGIN_REGISTER':  'Login / Join us',
            'KEY_NOTIFICATION':  'Notification',
            'KEY_DASHBOARD':  'Dashboard',
            'KEY_LANGUAGES':  'Languages',
            'KEY_HOME':       'Home',
            'KEY_REGISTER':   'Register',
            'KEY_LOGIN':      'Log in',
            'KEY_LOGOUT':     'Log out',
            'KEY_FOLLOW':     'Follow',
            'KEY_FOLLOWER':   'Follower',
            'KEY_UNFOLLOW':   'Unfollow',
            'KEY_FOLLOWING':  'Following',
            'KEY_POST':       'Post',
            'KEY_POSTED':     'Posted',
            'KEY_UPVOTE':     'Upvote',
            'KEY_UPVOTED':    'Upvoted',
            'KEY_DWN_VOTE':   'Downvote',
            'KEY_DWN_VOTED':  'Downvoted',
            'KEY_VIEW':       'View',
            'KEY_REMOVE':     'Remove',
            'KEY_CANCEL':     'Cancel',
            'KEY_QUESTION':   'Question',
            'KEY_TOPIC':      'Topic',
            'KEY_CHG_PWD':    'Change Password',
            'KEY_PASSWORD':   'Password',
            'KEY_OLD_PWD':    'Old Password',
            'KEY_NEW_PWD':    'New Password',
            'KEY_NEW_PWD_C':  'New password confirmation',
            'KEY_SAVE':       'Save',
            'KEY_SAVE_DRAFT': 'Save as draft',
            'KEY_TAGS':       'Tags',
            'KEY_EXPLORE':    'Explore',
            'KEY_FEAT_CAT':    'Features categories',
            'KEY_COMMENTS':   'Comments',
            'KEY_REPLY':      'Reply',
            'KEY_PHOTO':      'Photo',
            'KEY_REVIEW':     'Review',
            'KEY_EDIT':       'Edit',
            'KEY_TREND':      'Trend',
            'KEY_TRENDING':   'Trending',
            'KEY_BOOKMARK':   'Bookmark',
            'KEY_HISTORY':    'History',
            'KEY_WRITE_REPLY':'Write a reply',
            'KEY_LATEST_FEED':'Latest Feed',
            'KEY_IN':         'in',
            'KEY_BY':         'by',

            //Remove topic
            'KEY_CONF_REMOVE':'Are you sure you want to remove?',
            'KEY_CONF_REM_C': 'Once remove, you will not be ableto to get this topic back',


            //SENTENCE
            'KEY_WHAT_ON_UR_MIND':  'What\'s on your mind?',
            'KEY_YOU_WANT_FOLLOW':  'You may want to follow',
            'KEY_NO_ACCT_REGISTER': 'Don\'t have account? Join us',
            'KEY_CANT_CHNG_USER':   'Don\'t have account? Register',
            'KEY_YOUR_ACCOUNT':     'Your account',
            'KEY_NOTHING_HERE':     'Nothing here, yet',
            'KEY_WHO_TO_FOLLOW':    'Who to follow',
            'KEY_CAT_WILL_APPEAR':  'Follow some categories and it will appear here',
            'KEY_WHT_UR_STORY':     'What\'s your story',
            'KEY_WRT_COMMENT':      'Write a comment',
            'KEY_FORGOT_PWD':       'Forgot Your Password?',
            'KEY_UPLOAD_PHOTO':     'Forgot Your Password?',
            'KEY_TAGS_FOLLOW':      'Tags you are following',


            //USER RATING
            'KEY_USER_RATING':  'User Rating',
            'KEY_DETAILS':      'Details',

            //USER INPUT
            'KEY_FIRSTNAME':  'First name',
            'KEY_LASTNAME':   'Last name',
            'KEY_BIRTHDAY':   'Birthday',
            'KEY_MONTH':      'Month',
            'KEY_DAY':        'Day',
            'KEY_EMAIL':      'Email',
            'KEY_CONF_EMAIL': 'Confirm Email',
            'KEY_GENDER':     'Gender',
            'KEY_MALE':       'Male',
            'KEY_FEMALE':     'Female',
            'KEY_USERNAME':   'Username',
            'KEY_LOCATION':   'Location',
            'KEY_REMEMBER_ME':'Remember me',

            //User Edit
            'KEY_ED_PROFILE': 'Edit Profile',
            'KEY_ED_CHG_PWD': 'Change Password',
            'KEY_ED_PROFILE': 'Edit Profile',
            'KEY_ED_SITE':    'Website',
            'KEY_ED_PHONE':   'Phone',
            'KEY_ED_BIO':     'Biography',

        });

        $translateProvider.translations('ไทย', {
            'KEY_LOGIN_REGISTER':  'เข้าสู่ระบบ / สมัครใช้',
            'KEY_DASHBOARD':  'ห้องทั้งหมด',
            'KEY_LANGUAGES':  'ภาษา',
            'KEY_HOME':       'หน้าแรก',
            'KEY_REGISTER':   'สมัครใช้',
            'KEY_LOGIN':      'เข้าใช้',
            'KEY_FOLLOW':     'ติดตาม',
            'KEY_POST':       'โพสต์'
        });

        $translateProvider.preferredLanguage('Eng');
    }])
//# sourceMappingURL=all.js.map
