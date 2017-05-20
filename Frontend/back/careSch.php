<?php
//此用户关注此高校
define('Root_Path', preg_replace('/\\\Frontend\\\back/', '', dirname(__FILE__)));
require_once Root_Path . '/Model/schcare.php';

$user_id     = $_POST['user_id'];
$sch_id     = $_POST['sch_id'];

$schcare=new schcare();
$row=$schcare->isCared($user_id,$sch_id);

if (!empty($row)) {
	//此用户当前关注过此高校
	$schcare->upCare1($user_id,$sch_id);
	echo json_encode('success');
}else{
	//此用户从未关注此高校
	$schcare->insert($user_id,$sch_id);
	echo json_encode('success');
}