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
    })