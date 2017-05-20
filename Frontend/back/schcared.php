<?php
//此用户关注的高校
define('Root_Path', preg_replace('/\\\Frontend\\\back/', '', dirname(__FILE__)));
require_once Root_Path . '/Model/schcare.php';
require_once Root_Path . '/Model/school.php';

$sqlArr  = array();
$name    = array();
$last    = $_POST['last'];
$amount  = $_POST['amount'];
$user_id = $_POST['user_id'];
$start   = ($last - 1) * $amount;

$schcare = new schcare();
$school  = new school();

$row           = $schcare->moreSchCare($user_id, $start, $amount);
$rows          = $schcare->allSchCare($user_id);
$list["count"] = count($rows);

foreach ($row as $key => $value) {
    $sqlArr[] = $value['sch_id'];
}

$sch = $school->find_all_arrId($sqlArr);
foreach ($sch as $key => $value) {
    $list[] = [
        'name' => $value['name'],
    ];
}

if (!empty($list)) {
    echo json_encode($list);
}
