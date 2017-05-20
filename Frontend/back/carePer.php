<?php
//此用户关注此校友
define('Root_Path', preg_replace('/\\\Frontend\\\back/', '', dirname(__FILE__)));
require_once Root_Path . '/Model/percare.php';

$user_id     = $_POST['user_id'];
$per_id     = $_POST['per_id'];

$percare=new percare();
$row=$percare->isCared($user_id,$per_id);

if (!empty($row)) {
	//此用户当前关注过此高校
	$percare->upCare1($user_id,$per_id);
	echo json_encode('success');
}else{
	//此用户从未关注此高校
	$percare->insert($user_id,$per_id);
	echo json_encode('success');
}