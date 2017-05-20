<?php
//用户密码修改
define('Root_Path', preg_replace('/\\\Frontend\\\back/', '', dirname(__FILE__)));
require_once Root_Path . '/Model/users.php';

$mail     = $_POST['mail'];
$password = md5($_POST['password']);

$users = new users();
$row   = $users->find_by_mail($mail);

if (empty($row)) {
    echo json_encode('occupied');
} else {
    $users->modify_password($password, $mail);
    echo json_encode('success');
}