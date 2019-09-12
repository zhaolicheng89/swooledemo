<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/8/19
 * Time: 10:16
 */
//创建HTTP服务器对象
$host = "0.0.0.0";
$port = 9501;
$server = new swoole_http_server($host, $port);
//var_dump($server);

//设置服务器运行参数
$configs = [];
$configs["worker_num"] = 2;//设置Worker工作进程数量
$configs["daemonize"] = 0;//设置是否已后台守护进程运行
$server->set($configs);

//注册监听客户端HTTP请求回调事件
$server->on("request", function(swoole_http_request $request, swoole_http_response $response) use($server){
    print_r($request->post);//获取post过来的参数
   // var_dump($response);
    //获取客户端文件描述符
    $fd = $request->fd;
    if(!empty($fd)){
        //获取连接信息
        $clientinfo = $server->getClientInfo($fd);
      //  var_dump($clientinfo);
        //获取收包时间
         // var_dump($clientinfo["last_time"]);
        print_r(date('Y-m-d H:i:s', $clientinfo["last_time"]));
    }
    //响应客户端HTTP请求
    $response->write("success");//向客户端写入响应数据
    $response->end();//浏览器中输出结果
});

//启动服务器
$server->start();