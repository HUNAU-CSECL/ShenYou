<?php
//加载用户所属高校
define('Root_Path', preg_replace('/\\\Frontend\\\back/', '', dirname(__FILE__)));
require_once Root_Path . '/Model/school.php';
require_once Root_Path . '/Model/users.php';

$id = $_POST['id'];

$school = new school();
$users  = new users();

$row    = $users->find_by_id($id);
$school = $school->find_by_id($row['sch_id']);

echo json_encode($school['name']);
