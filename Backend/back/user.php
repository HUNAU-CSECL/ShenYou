<?php
//全部用户
define('Root_Path', preg_replace('/\\\Backend\\\back/', '', dirname(__FILE__)));
require Root_Path . '/Model/users.php';

$list = array();

$obj  = new users();
$rows = $obj->allUsers();

foreach ($rows as $key => $value) {
    $list[] = [
        'id'   => $value['id'],
        'name' => $value['name'],
        'mail' => $value['mail'],
        'type' => $value['type'],
    ];
}

if (!empty($list)) {
    echo json_encode($list);
}
