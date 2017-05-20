<?php
//政界职位加载更多
define('Root_Path', preg_replace('/\\\Backend\\\back/', '', dirname(__FILE__)));
require Root_Path . '/Model/lea_jobs.php';

$list   = array();
$last   = $_POST['last'];
$amount = $_POST['amount'];

$obj  = new lea_jobs();
$rows = $obj->allGrade($last,$amount);

foreach ($rows as $key => $value) {
    $list[] = [
        'name' => $value['name'],
    ];
}
$list["count"] = count($rows);
if (!empty($list)) {
    echo json_encode($list);
}
