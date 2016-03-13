angular.module('App')
    .controller('ProfileCtrl',function($http,$mdToast,$timeout, $mdSidenav, $log){

        var profileCtrl = this;

        profileCtrl.profileDescription  =   '';
        profileCtrl.notificationList    =   '';
        profileCtrl.unreadNotification  =   0;
        profileCtrl.userBookmark        =   0;

        profileCtrl.toggleRight = buildToggler('alertSideNav');
        profileCtrl.isOpenRight = function(){
            return $mdSidenav('alertSideNav').isOpen();
        };


        var last = {
            bottom: false,
            top: true,
            left: false,
            right: true
        };

        profileCtrl.toastPosition = angular.extend({},last);
        profileCtrl.getToastPosition = function() {
            sanitizePosition();
            return Object.keys(profileCtrl.toastPosition)
                .filter(function(pos) { return profileCtrl.toastPosition[pos]; })
                .join(' ');
        };
        function sanitizePosition() {
            var current = profileCtrl.toastPosition;
            if ( current.bottom && last.top ) current.top = false;
            if ( current.top && last.bottom ) current.bottom = false;
            if ( current.right && last.left ) current.left = false;
            if ( current.left && last.right ) current.right = false;
            last = angular.extend({},current);
        }


        profileCtrl.getUserStat = function(uuid)
        {
            var ref = new Firebase("https://qanya.firebaseio.com/user/"+uuid+"/stat/");
            ref.on("value", function(snapshot) {
                profileCtrl.userFollower = snapshot.val().follower;
                profileCtrl.userUpvoted  = snapshot.val().upvote;
            })
        }


        profileCtrl.profileImage = function(files)
        {
            angular.forEach(files, function(flowFile, i){
                console.log(flowFile);
                var fileReader = new FileReader();
                fileReader.onload = function (event) {
                    var uri = event.target.result;
                    //profileCtrl.imageStrings[i] = uri;
                    $.post( "/api/previewImage/", { data: uri} )
                        .done(function( response ) {
                            $http.post( "/upload-profileImage", { img: response} )
                                .then(function( response ) {
                                    $('#profilePhoto').attr( "src", response.data);
                                    $mdToast.show(
                                        $mdToast.simple()
                                            .textContent('Save')
                                            .position(profileCtrl.getToastPosition())
                                            .hideDelay(3000)
                                    );
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
                    console.log(response);
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

                    $mdToast.show(
                        $mdToast.simple()
                            .textContent('Save!')
                            .position('top right')
                            .hideDelay(3000)
                    );
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


