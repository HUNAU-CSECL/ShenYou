<?php
set_time_limit(0);
//更新指定政府
define('Root_Path', preg_replace('/\\\Backend\\\back/', '', dirname(__FILE__)));
require Root_Path . '/Backend/back/uppol.php';

$eng_name = $_POST['eng_name'];
$array    = array(1 => $eng_name);

$obj = new uppol();
//更新指定政府
$obj->update($array);

echo json_encode('success');
