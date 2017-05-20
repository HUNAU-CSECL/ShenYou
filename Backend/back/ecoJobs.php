<?php
//商界职位加载更多
define('Root_Path', preg_replace('/\\\Backend\\\back/', '', dirname(__FILE__)));
require Root_Path . '/Model/eco_jobs.php';

$list   = array();
$last   = $_POST['last'];
$amount = $_POST['amount'];

$obj  = new eco_jobs();
$row  = $obj->nullGrade($last, $amount);
$rows = $obj->allNullGrade();
foreach ($row as $key => $value) {
    $list[] = [
        'name' => $value['name'],
    ];
}
$list["count"] = count($rows);
if (!empty($list)) {
    echo json_encode($list);
}
