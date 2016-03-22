angular.module('App')
.directive('topicTally', function () {
    return {
        //controller: 'TopicCtrl as topicCtrl',
        restrict: 'EA',
        transclude:   true,
        templateUrl: '/assets/templates/topic-tally.html',
        scope: {
            data: '='
        }
    }
})

.directive('profileBadge', function () {
    return {
        controller: 'ProfileCtrl as profileCtrl',
        restrict: 'EA',
        transclude:   true,
        templateUrl: '/assets/templates/profile-badge.html'
    }
})

.directive('postedPhotos', function () {
    return {
        controller: 'ProfileCtrl as profileCtrl',
        restrict: 'EA',
        transclude:   true,
        templateUrl: '/assets/templates/posted-photos.html',
        scope: {
            data: '='
        }
    }
})