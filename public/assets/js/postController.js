angular.module('App')
    .controller('PostCtrl',function($http){

        var postCtrl = this;

        postCtrl.postTopic = function()
        {
            console.log(postCtrl.title);
            console.log("post")
            /*$http({
                method : "GET",
                url : "welcome.htm"
            }).then(function mySucces(response) {
                $scope.myWelcome = response.data;
            }, function myError(response) {
                $scope.myWelcome = response.statusText;
            });*/
        }

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
                            //console.log();
                        })

                    $('#postBodyId').append(document.createTextNode('<img src=\"'+uri+'\">'));
                };
                fileReader.readAsDataURL(flowFile.file);
            });
        };
    })