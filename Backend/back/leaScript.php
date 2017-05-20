<?php
//学界加载更多
define('Root_Path', preg_replace('/\\\Backend\\\back/', '', dirname(__FILE__)));
require Root_Path . '/Model/script.php';

$list   = array();
$last   = $_POST['last'];
$amount = $_POST['amount'];

$obj = new script();
$row = $obj->moreScript($last, $amount,3);
$rows = $obj->allScript(3);
foreach ($row as $key => $value) {
    $list[] = [
        'name'     => $value['name'],
        'eng_name' => $value['eng_name'],
    ];
}
$list["count"] = count($rows);
if (!empty($list)) {
    echo json_encode($list);
}
