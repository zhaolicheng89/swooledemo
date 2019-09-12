<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/8/19
 * Time: 15:26
 */
$server = new swoole_websocket_server("0.0.0.0", 9501);
$server->on('open', function (swoole_websocket_server $server, $request) {
    file_put_contents( __DIR__ .'/log.txt' , $request->fd);
});

$server->on('message', function (swoole_websocket_server $server, $frame) {
    global $client;
    $data = $frame->data;
//    $m = file_get_contents( __DIR__ .'/log.txt');
//    for ($i=1 ; $i<= $m ; $i++) {
//       // echo PHP_EOL . '  i is  ' . $i .  '  data  is '.$data  . '  m = ' . $m;
//        $server->push($i, $data );
//    }
    // 广播
    foreach($server->connections as $fd) {
        $server->push($fd, $data);
    }

});

$server->on('close', function ($ser, $fd) {
    echo "client {$fd} closed\n";
});

$server->start();