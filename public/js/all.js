function getFeedCate(slug){
    $.get( "/getFeed/", { slug: slug } )
        .done(function( data ) {
            $('#homeFeed').html(data);
        });
}

var app = angular.module('App', ['ngMaterial','flow']);

angular.module('App')
    .controller('PostCtrl',function($http){

        var postCtrl = this;

        postCtrl.replyComment ='';

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
                    window.location = response;
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
                            $('#contentBody').append('<img src=\"'+response+'\" class=\"img-responsive\">');
                        })
                };
                fileReader.readAsDataURL(flowFile.file);
            });

        };


        //Post re
        postCtrl.postReply = function(postId)
        {
            console.log(postId);
            console.log(postCtrl.replyComment);
           /* $.post( "/api/postReply/", { data: data} )
                .done(function( response ) {
                    window.location = response;
                })*/
        }
    })
//# sourceMappingURL=all.js.map
