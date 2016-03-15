angular.module('App')
    .controller('ProfileCtrl',function($http,$mdToast,$timeout, $mdSidenav, $log,toastr){

        var profileCtrl = this;

        profileCtrl.profileDescription  =   '';
        profileCtrl.notificationList    =   '';
        profileCtrl.unreadNotification  =   0;
        profileCtrl.userBookmark        =   0;

        profileCtrl.toggleRight = buildToggler('alertSideNav');
        profileCtrl.isOpenRight = function(){
            return $mdSidenav('alertSideNav').isOpen();
        };

        profileCtrl.getUserStat = function(uuid)
        {
            var ref = new Firebase("https://qanya.firebaseio.com/user/"+uuid+"/stat/");
            ref.on("value", function(snapshot) {

                /*var key = 'bookmarks_'+topic_uuid;
                postCtrl[key]  = snapshot.val();*/

                var key = 'userFollower_'+uuid;
                profileCtrl[key]  = snapshot.val().follower;

                var key = 'userUpvote_'+uuid;
                profileCtrl[key]  = snapshot.val().upvote;

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
                        $('#userBookmark').html(response.data);
                    })

                //profileCtrl.userBookmark = snapshot.val();
            });
        }

        profileCtrl.getUserHistory = function(user_uuid)
        {
            var ref = new Firebase("https://qanya.firebaseio.com/user/"+user_uuid+"/history");
            ref.on("value",function (snapshot) {

                $http.post('/user/getHistory',
                    {data: snapshot.val()})
                    .then(function(response){
                        $('#userViewHistory').html(response.data);
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


