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
        restrict: 'E',
        transclude:   true,
        scope: {
            data: '='
        },
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


//Preview images
.directive('previewImages', function () {
    return {
        controller: 'PostCtrl as postCtrl',
        restrict: 'E',
        transclude: true,
        scope: {
            data: '='
        },
        templateUrl: '/assets/templates/preview-images.html'
    }
})

.directive('reviewTopic', function () {
    return {
        controller: 'PostCtrl as postCtrl',
        restrict: 'E',
        transclude: true,
        scope: {
            data: '='
        },
        templateUrl: '/assets/templates/review-topic.html'
    }
})

//Review score form
.directive('reviewForm', function () {
    return {
        controller: 'PostCtrl as postCtrl',
        restrict: 'E',
        transclude: true,
        scope: {
            data: '='
        },
        templateUrl: '/assets/templates/review-form.html'
    }
})


//Review score form
.directive('minFeedList', function () {
    return {
        controller: 'PostCtrl as postCtrl',
        restrict: 'E',
        transclude: true,
        scope: {
            data: '='
        },
        templateUrl: '/assets/templates/min-feed-list.html'
    }
})

//Review score form
.directive('feedList', function () {
    return {
        controller: 'PostCtrl as postCtrl',
        restrict: 'E',
        transclude: true,
        scope: {
            data: '='
        },
        templateUrl: '/assets/templates/feed-list.html'
    }
})