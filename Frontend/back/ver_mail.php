<?php
//验证邮箱是否被注册
define('Root_Path', preg_replace('/\\\Frontend\\\back/', '', dirname(__FILE__)));
require_once Root_Path . '/Model/users.php';

$mail     = $_POST['mail'];

$users = new users();
$row   = $users->find_by_mail($mail);

if (!empty($row)) {
    echo json_encode('true');
}else{
	echo json_encode('false');
}
