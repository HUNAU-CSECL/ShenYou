<?php
//检测此用户是否关注此高校
define('Root_Path', preg_replace('/\\\Frontend\\\back/', '', dirname(__FILE__)));
require_once Root_Path . '/Model/schcare.php';

$user_id     = $_POST['user_id'];
$sch_id     = $_POST['sch_id'];

$schcare=new schcare();
$row=$schcare->isCare($user_id,$sch_id);

if (!empty($row)) {
	echo json_encode('1');
}else{
	echo json_encode('0');
}