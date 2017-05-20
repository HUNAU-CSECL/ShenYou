<?php
//插入reason表$num:人的ID$string:原由内容
define('Root_Path', preg_replace('/\\\Backend\\\back/', '', dirname(__FILE__)));
require_once Root_Path . '/Model/reason.php';
require_once Root_Path . '/Model/persons.php';

$per_id        = $_POST['per_id'];
$reason_string = $_POST['reason'];

$reason  = new reason();
$persons = new persons();

$reason->insert($per_id, $reason_string);
$persons->hide($per_id);
echo json_encode('success');
