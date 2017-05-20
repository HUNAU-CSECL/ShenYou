<?php
//用户注册与验证
define('Root_Path', preg_replace('/\\\Frontend\\\back/', '', dirname(__FILE__)));
require_once Root_Path . '/Model/users.php';

$name     = $_POST['name'];
$mail     = $_POST['mail'];
$password = md5($_POST['password']);
$sch_id   = $_POST['sch_id'];

$users = new users();
$row   = $users->find_by_mail($mail);

if (!empty($row)) {
    echo json_encode('occupied');
} else {
    $users->insert($name, $mail, $password, $sch_id);
    $id = $users->insertId();
    echo json_encode($id);
}
