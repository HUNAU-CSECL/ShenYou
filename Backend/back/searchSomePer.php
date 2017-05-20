<?php
//搜索人框返回提示(模糊搜索)
define('Root_Path', preg_replace('/\\\Backend\\\back/', '', dirname(__FILE__)));
require Root_Path . '/Model/persons.php';

$name = $_POST['name'];
$list = array();

$obj  = new persons();
$rows = $obj->find_by_keyword(trim($name));
foreach ($rows as $key => $value) {
    $list[] = [
        'id'   => $value['id'],
        'name' => $value['name'],
    ];
}
echo json_encode($list);
