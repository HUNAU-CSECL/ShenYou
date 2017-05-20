<?php
//此用户关注的校友
define('Root_Path', preg_replace('/\\\Frontend\\\back/', '', dirname(__FILE__)));
require_once Root_Path . '/Model/percare.php';
require_once Root_Path . '/Model/persons.php';

$sqlArr  = array();
$name    = array();
$last    = $_POST['last'];
$amount  = $_POST['amount'];
$user_id = $_POST['user_id'];
$start   = ($last - 1) * $amount;

$percare = new percare();
$persons = new persons();

$row           = $percare->morePerCare($user_id, $start, $amount);
$rows          = $percare->allPerCare($user_id);
$list["count"] = count($rows);

foreach ($row as $key => $value) {
    $sqlArr[] = $value['per_id'];
}

$per = $persons->find_all_arrId($sqlArr);

foreach ($per as $key => $value) {
    $list[] = [
        'id'   => $value['id'],
        'name' => $value['name'],
    ];
}

if (!empty($list)) {
    echo json_encode($list);
}
