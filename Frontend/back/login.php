<?php
//用户登录验证
define('Root_Path', preg_replace('/\\\Frontend\\\back/', '', dirname(__FILE__)));
require_once Root_Path . '/Model/users.php';

$mail     = $_POST['mail'];
$password = md5($_POST['password']);

$list = array();

$users = new users();
$row   = $users->login($mail, $password);

if (!empty($row)) {
    foreach ($row as $key => $value) {
        $list[] = [
            'id'   => $value['id'],
            'name' => $value['name'],
            'mail'=>$value['mail'],
            'type' => $value['type'],
        ];
    }
    echo json_encode($list);
}else{
	echo json_encode('false');
}
