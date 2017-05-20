<?php
//首页省份索引返回
define('Root_Path', preg_replace('/\\\Frontend\\\back/', '', dirname(__FILE__)));
require Root_Path . '/Model/school.php';

$pro_id = $_POST['pro_id'];

$obj     = new school();
$rows    = $obj->find_by_proid($pro_id);

foreach ($rows as $key => $value) {
	$list[]=$value['name'];
}

echo json_encode($list);
