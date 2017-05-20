<?php
set_time_limit(0);
//更新指定政界职位等级
define('Root_Path', preg_replace('/\\\Backend\\\back/', '', dirname(__FILE__)));
require Root_Path . '/Model/pol_jobs.php';

$name=$_POST['name'];
$grade = $_POST['grade'];

$obj      = new pol_jobs();
$obj->insertGrade($name, $grade);

echo json_encode('success');