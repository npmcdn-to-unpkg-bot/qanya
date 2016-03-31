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


