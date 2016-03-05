angular.module('App')
    // Topic list
    .factory('Topics', function ($firebaseObject, $firebaseArray, FirebaseUrl) {
        var ref = new Firebase(FirebaseUrl)
        var topics = $firebaseObject(ref)
        var topicsArr = $firebaseArray(ref)
        var topicKey = '';

        var Topics = {

            // Reply listing
            upvoteURL: function (user_uuid){
                return ref.child('user/'+user_uuid)
            },
            replyList: function (topic_uuid) {
                var data = ref.child(topic_uuid + '/replies');
                console.log(data);
                return $firebaseArray(data)
            },
            ref: ref
        }
        return Topics;

    })
