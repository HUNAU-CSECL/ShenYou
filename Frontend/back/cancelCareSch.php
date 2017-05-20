<?php
//此用户取消关注此高校
define('Root_Path', preg_replace('/\\\Frontend\\\back/', '', dirname(__FILE__)));
require_once Root_Path . '/Model/schcare.php';

$user_id = $_POST['user_id'];
$sch_id  = $_POST['sch_id'];

$schcare=new schcare();
$schcare->upCare0($user_id, $sch_id);
echo json_encode('success');
