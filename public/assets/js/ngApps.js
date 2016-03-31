//Angular config and modules

var app = angular.module('App', ['ngMaterial','flow','angularMoment','firebase',
                                 'toastr',
                                 'angular.filter',
                                 'ngCookies','ngSanitize',
                                 'pascalprecht.translate'])

.constant('FirebaseUrl', 'https://qanya.firebaseio.com/')



.config(['$translateProvider', function ($translateProvider) {
    // Enable escaping of HTML
    $translateProvider.useSanitizeValueStrategy('sanitize');
}])


.config(["$mdThemingProvider", function ($mdThemingProvider) {
    $mdThemingProvider.definePalette('slack', {
        '50': '5DB09D',
        '100': 'ffcdd2',
        '200': 'ef9a9a',
        '300': 'e57373',
        '400': '5DB09D',
        '500': '684666', // primary colour
        '600': 'e53935',
        '700': 'd32f2f',
        '800': 'c62828',
        '900': 'b71c1c',
        'A100': 'ff8a80',
        'A200': 'ff5252',
        'A400': 'ff1744',
        'A700': 'd50000',
        'contrastDefaultColor': 'light', // whether, by default, text (contrast)
        // on this palette should be dark or light
        'contrastDarkColors': ['50', '100', // hues which contrast should be 'dark' by default
            '200', '300', '400', 'A100'],
        'contrastLightColors': undefined // could also specify this if default was 'dark'
    })
    $mdThemingProvider.theme('default')
        .primaryPalette('slack')
}])


//For removing that <p> from topic header
.filter('htmlToPlaintext', function() {
        return function(text) {
            return  text ? String(text).replace(/<[^>]+>/gm, '') : '';
        };
    }
)

//Limit the number of length
//http://jsfiddle.net/tuyyx/
.filter('truncate', function () {
    return function (text, length, end) {
        if (isNaN(length))
            length = 10;

        if (end === undefined)
            end = "...";

        if (text.length <= length || text.length - end.length <= length) {
            return text;
        }
        else {
            return String(text).substring(0, length-end.length) + end;
        }

    };
});