<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/8/19
 * Time: 9:41
 */
$phone ='13152059301';//支持字符串或者二进制
$client = new \swoole_client(SWOOLE_SOCK_TCP, SWOOLE_SOCK_SYNC);
$ret = $client->connect("127.0.0.1", 8082);
if(empty($ret)){
    echo 'error!connect to swoole_server failed';
} else {
    if($phone){
        $client->send($phone);//手机号
    }
    echo "SUCCESS";
}