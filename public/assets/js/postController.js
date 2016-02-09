angular.module('App')
    .controller('PostCtrl',function($http){

        var postCtrl = this;

        postCtrl.displayname ={
            'text' :'',
            'saveBtn':  false,
            'alert':    false
        }


        postCtrl.checkDisplayname = function(){

            $http.post('/check-name', {name: postCtrl.displayname.text})
                .then(function(response,data){
                console.log(response);
                console.log(data);
                if(response.data == "0"){
                    postCtrl.displayname.saveBtn = true;
                    postCtrl.displayname.alert   = false;
                }else{
                    postCtrl.displayname.saveBtn = false;
                    postCtrl.displayname.alert   = true;
                }
            });

            /*$.get( "/check-name/", { data: postCtrl.displayname.text } )
                .done(function( data ) {
                    console.log("return "+data);
                    if(data == 0){
                        console.log("okay to save");
                        postCtrl.displayname.saveBtn = true;
                        postCtrl.displayname.alert   = false;
                        console.log(postCtrl.displayname.alert)
                        console.log(postCtrl.displayname.saveBtn)
                    }
                    else{
                        postCtrl.displayname.saveBtn = false;
                        postCtrl.displayname.alert   = true;
                        console.log(postCtrl.displayname.alert)
                        console.log(postCtrl.displayname.saveBtn)
                    }
                });*/

        }

        postCtrl

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
                    window.location(response);
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