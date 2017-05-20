<?php
//取消更新高校曾用名
define('Root_Path', preg_replace('/\\\Frontend\\\back/', '', dirname(__FILE__)));
require Root_Path . '/Model/school.php';

$name = $_POST['name'];

$school = new school();
$row    = $school->find_by_name($name);
$list[] = [
    'id'        => $row['id'],
    'name'      => $row['name'],
    'eng_name'  => $row['eng_name'],
    'web'       => $row['web'],
    'introduct' => $row['introduct'],
];

echo json_encode($list);
