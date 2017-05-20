<?php
//升级普通用户为管理员
define('Root_Path', preg_replace('/\\\Backend\\\back/', '', dirname(__FILE__)));
require Root_Path . '/Model/users.php';

$id=$_POST['id'];

$obj  = new users();
$obj->toAdmin($id);

echo json_encode('success');