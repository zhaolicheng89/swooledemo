<?php 
  
 class TimerServer 
{ 
 	private $serv; 
    private $test;
   
 	public function __construct() { 
 		$this->serv = new swoole_server("0.0.0.0", 9501);
         $this->serv->set(array( 
             'worker_num' => 1, 
             'daemonize' => false, 
              'max_request' => 10000, 
             'dispatch_mode' => 2, 
             'debug_mode'=> 1 , 
         )); 
  
         $this->serv->on('WorkerStart', array($this, 'onWorkerStart')); 
         $this->serv->on('Connect', array($this, 'onConnect')); 
         $this->serv->on('Receive', array($this, 'onReceive')); 
         $this->serv->on('Close', array($this, 'onClose')); 
                 // bind callback 
//       $this->serv->on('Timer', array($this, 'onTimer')); 
         $this->serv->start(); 
 	} 
  
 	public function onWorkerStart( $serv , $worker_id) { 
 		// 在Worker进程开启时绑定定时器 
         echo "onWorkerStart\n"; 
         
         $this->test=2;
         $aaa=3;
//       swoole_timer_tick(5000,function($timer_id,$parms) use($aaa){
//       	echo 'timer_tick is running';echo '---------';
//       	echo "recev:{$parms}";echo "\n";
//       	var_dump($aaa);echo "\n";echo "\n";
//       },'hello');
//       
         
        // $this->test->index=3;
         swoole_timer_tick(5000,array($this,'onTick'),'hello');
         
         
         
         
         // 只有当worker_id为0时才添加定时器,避免重复添加 
//       if( $worker_id == 0 ) { 
//       	$serv->addtimer(100); 
// 	        $serv->addtimer(500); 
//           $serv->addtimer(1000); 
//       } 
     } 
  
     public function onConnect( $serv, $fd, $from_id ) { 
         echo "Client {$fd} connect\n"; 
     } 
  
     public function onReceive( swoole_server $serv, $fd, $from_id, $data ) { 
     	echo 'timer after';
     	   swoole_timer_after(1000,function() use($serv,$fd){
     		$serv->send($fd,'hello later');
     	});
     	
        echo "Get Message From Client {$fd}:{$data}\n"; 
     } 
  
     public function onClose( $serv, $fd, $from_id ) { 
         echo "Client {$fd} close connection\n"; 
     }
    public function onTick($timer_id, $parms=null) {
            
			$str='这里是北斗卫星';
			//客户端传数据
			$cli = new swoole_http_client('127.0.0.1', 9501);
            $cli->on('message', function ($_cli, $frame) {
                var_dump($frame);
            });
            $cli->upgrade('/', function ($cli) {
                echo $cli->body;
                $cli->push("hello world");
            });
 	}
 }
 new TimerServer(); 
