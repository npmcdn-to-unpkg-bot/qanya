angular.module('App')
    .controller('ProfileCtrl',function($http,$mdToast){

        var profileCtrl = this;

        profileCtrl.profileDescription='';

        profileCtrl.updateDescription = function()
        {
            $http.post('/user/update-description',
                {name: $('#profileDescription').html()})
                .then(function(response){

                    $mdToast.show(
                        $mdToast.simple()
                            .textContent('Save!')
                            .position('top')
                            .hideDelay(3000)
                    );
                });
        }
    })