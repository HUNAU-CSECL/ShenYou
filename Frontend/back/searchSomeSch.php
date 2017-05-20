<?php
//首页搜索框返回提示(模糊搜索)
define('Root_Path', preg_replace('/\\\Frontend\\\back/', '', dirname(__FILE__)));
require Root_Path . '/Model/school.php';

$name = $_POST['name'];

$obj  = new school();
$rows = $obj->find_by_keyword(trim($name));
foreach ($rows as $key => $value) {
    $list[] = [
        'id'   => $value['id'],
        'name' => $value['name'],

    ];
}

echo json_encode($list);
