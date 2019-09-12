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
    // 广播
//    foreach($server->connections as $fd) {
//        $server->push($fd, $data);
//    }
    $data = json_decode($frame->data);
    if($data->type==1) {
        $message =  $data->message;
        $response = array(
            'type' => 1,    // 1代表系统消息，2代表用户聊天
            'message' => $message
        );
    }
     elseif($data->type==2) {
        // $message = '欢迎' . $data->message . '加入了聊天室';
         $message=$data->message;
         $response = array(
             'type' =>2,    // 1代表系统消息，2代表用户聊天
             'message' => $message,
             'username'=>$frame->fd
         );
     }
    foreach ($server->connections as $fd) {
        $server->push($fd, json_encode($response));
    }
});

$server->on('close', function ($server, $fd) {
    $response = array(
        'type' => 1,    // 1代表系统消息，2代表用户聊天
        'message' => $fd . '离开了聊天室'
    );
    //$ser->task($response);
    foreach ($server->connections as $fd) {
        $server->push($fd, json_encode($response));
    }
});

$server->start();