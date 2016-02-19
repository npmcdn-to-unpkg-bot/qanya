angular.module('App')
    .controller('PostCtrl',function($http){

        var postCtrl = this;

        postCtrl.displayname ={
            'text' :'',
            'saveBtn':  false,
            'alert':    false
        }

        postCtrl.topicReply = '';


        //Follow categories
        postCtrl.followCate = function(slug){
            console.log("test "+slug);

            $http.post('/follow-cate/', {slug: slug})
                .then(function(response){
                    console.log(response)
                });

        }


        postCtrl.getFeedCate = function(slug){
            postCtrl.slug = slug;
            $http.post('/getFeed/', {slug: slug})
                .then(function(response){
                    console.log(response)
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
        postCtrl.postReply = function(uuid)
        {
            $http.post('/replyTopic', {uuid: uuid,
                                       data: postCtrl.topicReply })
                .then(function(response){
                    console.log('postctrl.js' + response);
                    console.log(response.data);
                    var Redis = require('ioredis');
                    var redis = new Redis();
                    redis.subscribe('reply-'+uuid, function(err, count) {
                    });

                    socket.on('reply-'+uuid+":App\\Events\\TopicReply", function(message){
                        $('#reply-'+uuid).text(message.data);
                    });

                    /*socket.on("App\\Events\\UserReply", function(message){
                        console.log(message);
                    });*/
                })
        }

        postCtrl.postTopic = function()
        {
            var imgIds = new Array();

            $("div#contentBody img").each(function(){
                imgIds.push($(this).attr('src'));
            });

            var data = { title:         postCtrl.title,
                         categories:    postCtrl.categories,
                         body:          $('#contentBody').html(),
                         images:         imgIds
                        };
            $.post( "/api/postTopic/", { data: data} )
                .done(function( response ) {
                    //window.location = response;
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
                            $('#contentBody').append('<img src=\"'+response+'\">');
                        })
                };
                fileReader.readAsDataURL(flowFile.file);
            });

        };
    })