<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/8/19
 * Time: 14:37
 */

$timer=Swoole\Timer::tick(3000, function () {
    //业务代码o
    echo date('Y-m-d H:i:s',time());
    echo "time1 3000ms.\n";
    $url='http://www.swooledemo.com/timer_url.php';
    request_post($url);
   $str=1;
    //在指定的时间后执行函数
   Swoole\Timer::after(500, function() use ($str) {
       echo date('Y-m-d H:i:s',time());
       echo "timeout, $str\n";
    });
});



//Swoole\Timer::clear($timer); 清楚定时器

/*
 * 功能：发送数据
 * return
 */
function request_post($url ='',$param ='') {
    $postUrl = $url;
    $curlPost = $param;
    $ch = curl_init();//初始化curl
    curl_setopt($ch, CURLOPT_URL,$postUrl);//抓取指定网页
    curl_setopt($ch, CURLOPT_HEADER, 0);//设置header
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//要求结果为字符串且输出到屏幕上
    curl_setopt($ch, CURLOPT_POST, 1);//post提交方式
    curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8','Content-Length: ' . strlen($curlPost)));
    curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
    curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,false);

    $data = curl_exec($ch);//运行curl
    // var_dump(curl_errno($ch));die;
    curl_close($ch);
    return $data;
}

