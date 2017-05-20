<?php
set_time_limit(0);
//更新指定商界职位等级
define('Root_Path', preg_replace('/\\\Backend\\\back/', '', dirname(__FILE__)));
require_once Root_Path . '/Backend/back/functions.php';
require_once Root_Path . '/Model/eco_jobs.php';

$name  = $_POST['name'];
$grade = $_POST['grade'];

$obj = new eco_jobs();
//更新商界职位等级
$obj->insertGrade($name, $grade);
//自动更新商界人影响力$string:职位名称$num:职位等级
autoEcoGrade($name, $grade);

echo json_encode('success');