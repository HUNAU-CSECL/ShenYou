<?php
set_time_limit(0);
//更新指定企业
define('Root_Path', preg_replace('/\\\Backend\\\back/', '', dirname(__FILE__)));
require Root_Path . '/Backend/back/upmarket.php';
require Root_Path . '/Backend/back/upeco.php';

$which = $_POST['eng_name'];

$upmarket = new upmarket();
//更新指定板块企业市值$which:hs、sb、usa
$upmarket->updatemarket($which);

$upeco = new upeco();
//更新指定板块企业人员$which:hs、sb、usa
$upeco->updateEco($which);

echo json_encode('success');