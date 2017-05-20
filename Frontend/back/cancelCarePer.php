<?php
//此用户取消关注此校友
define('Root_Path', preg_replace('/\\\Frontend\\\back/', '', dirname(__FILE__)));
require_once Root_Path . '/Model/percare.php';

$user_id = $_POST['user_id'];
$per_id  = $_POST['per_id'];

$percare=new percare();
$percare->upCare0($user_id, $per_id);
echo json_encode('success');