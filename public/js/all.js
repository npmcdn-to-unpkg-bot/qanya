$('html').click(function() {
    postCtrl.showForm=true
});
function getFeedCate(slug){
    $.get( "/getFeed/", { slug: slug } )
        .done(function( data ) {
            $('#homeFeed').html(data);
        });
}
var app = angular.module('App', ['ngMaterial']);

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
angular.module('App')
    .controller('HomeCtrl',function(){

        var homeCtrl = this;
    })
//# sourceMappingURL=all.js.map
