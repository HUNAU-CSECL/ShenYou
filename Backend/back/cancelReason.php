<?php
//撤销校友丑闻
define('Root_Path', preg_replace('/\\\Backend\\\back/', '', dirname(__FILE__)));
require_once Root_Path . '/Model/reason.php';
require_once Root_Path . '/Model/persons.php';

$per_id = $_POST['per_id'];

$reason  = new reason();
$persons = new persons();

$reason->cancel($per_id);
$persons->noHide($per_id);

echo json_encode('success');