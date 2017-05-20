<?php
//高校曾用名加载更多
define('Root_Path', preg_replace('/\\\Backend\\\back/', '', dirname(__FILE__)));
require Root_Path . '/Model/old.php';

$list   = array();
$last   = $_POST['last'];
$amount = $_POST['amount'];

$obj  = new old();
$row  = $obj->nullName($last,$amount);
$rows = $obj->allNullName();
foreach ($row as $key => $value) {
    $list[] = [
        'name' => $value['name'],
    ];
}
$list["count"] = count($rows);
if (!empty($list)) {
    echo json_encode($list);
}
