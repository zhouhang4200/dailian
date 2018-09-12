var app = require('http').createServer(handler);
var io = require('socket.io')(app);
var Redis = require('ioredis');
var redis = new Redis({
    port: 6379, // 端口
    host: '127.0.0.1', // 地址
    family: 4, // ip类型
    db: 6 // 数据库
});

app.listen(10086, function () {
    console.log('Server is running!') ;
});

function handler(req, res) {
    res.writeHead(200);
    res.end('');
}

io.on('connection', function (socket) {
    socket.on('message', function (message) {
        console.log(message)
    });
    socket.on('disconnect', function () {
        console.log('user disconnect')
    })
});

// 订阅 * 任意频道
redis.psubscribe('*', function (err, count) {
});

// 批量订阅接收来自 channel 频道的 message 信息, io.emit 将 message 发送到 channel:message.event ,io.emit('频道名', '信息')
redis.on('pmessage', function (subscrbed, channel, message) {
    message = JSON.parse(message);
    io.emit(channel + ':' + message.event, message.data);
});
