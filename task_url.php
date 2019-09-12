<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/8/19
 * Time: 13:04
 */
file_put_contents("b.txt",date('Y-m-d H:i:s').PHP_EOL,FILE_APPEND);
echo 'SUCCESS';