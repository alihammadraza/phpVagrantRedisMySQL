<?php
require_once 'config/config.php';

use Web\Services\redisService;

$redis = new redisService('predis'); //pass cli or predis as arg
$redis->setValue($_REQUEST);
$redis->save();
echo '{"status": "ok"}' . "\n";
?>