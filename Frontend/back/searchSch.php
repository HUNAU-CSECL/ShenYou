<?php
//首页搜索框提交搜索(精准搜索)
define('Root_Path', preg_replace('/\\\Frontend\\\back/', '', dirname(__FILE__)));
require Root_Path . '/Model/school.php';

$name = $_POST['name'];
$obj  = new school();

$row = $obj->find_by_name(trim($name));

if (!empty($row)) {
    echo json_encode($row['name']);
} else {
    $rows = $obj->find_by_keyword(trim($name));
    $num  = count($rows);
    if ($num == 1) {
        echo json_encode($rows[0]['name']);
    }
}
