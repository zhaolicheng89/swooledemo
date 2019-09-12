<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/8/19
 * Time: 13:03
 */
/**
 * 脚本任务系统
 */
    $serv = new \swoole_server("0.0.0.0", 8082);
    $serv->set([
        //关闭内置协程
        'enable_coroutine' => false,
    ]);
    //日志会记录你错误的数据
    $serv->set(array('task_worker_num' => 20, 'log_file' => './swoole.log'));
    $serv->on('Receive', function ($serv, $fd, $from_id, $data) {
        $task_id = $serv->task($data);
    });

    //执行任务模块
    $serv->on('Task', function ($serv, $task_id, $from_id, $data) {
            //1秒执行一次
            $num = 0;
        $timer_id = $serv->tick(1000, function ($id) use ($serv,$data,$num) {
            $url = "http://www.swooledemo.com/task_url.php";
            $result = request_post($url);
            if($result == 'SUCCESS'){
                echo 'success'. PHP_EOL;
                $serv->clearTimer($id);
            }
        });
        //10秒后销毁
        $serv->after(1000*10, function () use ($serv,$timer_id) {
            $serv->clearTimer($timer_id);
        });
        echo "Tasker进程接收到数据";
        echo "#{$serv->worker_id}\tonTask: [PID={$serv->worker_pid}]: task_id=$task_id, data_len=".strlen($data).".".PHP_EOL;
        $serv->finish($data);
    });
    //任务结束
    $serv->on('Finish', function ($serv, $task_id, $data) {
        echo "AsyncTask[$task_id] Finish: $data" . PHP_EOL;
    });
    $serv->start();


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