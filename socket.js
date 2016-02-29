var app = require('express')();
var http = require('http').Server(app);
var io = require('socket.io')(http);
var Redis = require('ioredis');
var redis = new Redis();
redis.psubscribe('*', function(err, count) {
});
redis.on('pmessage', function(subscribed, channel, message) {
    message = JSON.parse(message);
    io.emit(channel + ':' + message.event, message.data);
});
http.listen(3000, '128.199.157.98', function(){
    console.log('Listening on Port 3000');
});
