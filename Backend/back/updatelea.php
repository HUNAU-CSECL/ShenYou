<?php
set_time_limit(0);
//更新指定学界
define('Root_Path', preg_replace('/\\\Backend\\\back/', '', dirname(__FILE__)));
require Root_Path . '/Backend/back/upCAS.php';
require Root_Path . '/Backend/back/upCAE.php';

$eng_name = $_POST['eng_name'];

if ($eng_name == 'cas') {
    $upCAS = new upCAS();
    $upCAS->updateCAS();
} elseif ($eng_name == 'cae') {
    $upCAE = new upCAE();
    $upCAE->updateCAE();
}

echo json_encode('success');