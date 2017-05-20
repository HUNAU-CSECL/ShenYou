<?php
set_time_limit(0);
//取消更新高校曾用名
define('Root_Path', preg_replace('/\\\Backend\\\back/', '', dirname(__FILE__)));
require Root_Path . '/Model/old.php';

$name   = $_POST['name'];

$obj = new old();
$obj->cancelold($name);

echo json_encode('success');