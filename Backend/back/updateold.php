<?php
set_time_limit(0);
//更新高校曾用名
define('Root_Path', preg_replace('/\\\Backend\\\back/', '', dirname(__FILE__)));
require Root_Path . '/Model/old.php';

$name   = $_POST['name'];
$now_id = $_POST['now_id'];

$obj = new old();
$obj->upold($name, $now_id);
echo json_encode("success");