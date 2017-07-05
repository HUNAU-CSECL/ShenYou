<?php
//加载丑闻列表
define('Root_Path', preg_replace('/\\\Backend\\\back/', '', dirname(__FILE__)));
require Root_Path . '/Model/reason.php';
require Root_Path . '/Model/persons.php';
require Root_Path . '/Model/pol_perjob.php';
require Root_Path . '/Model/eco_perjob.php';
require Root_Path . '/Model/lea_perjob.php';
require Root_Path . '/Model/pol_jobs.php';
require Root_Path . '/Model/eco_jobs.php';
require Root_Path . '/Model/lea_jobs.php';
require Root_Path . '/Model/stockcode.php';

$last   = $_POST['last'];
$amount = $_POST['amount'];

$reason     = new reason();
$persons    = new persons();
$pol_perjob = new pol_perjob();
$eco_perjob = new eco_perjob();
$lea_perjob = new lea_perjob();
$pol_jobs   = new pol_jobs();
$eco_jobs   = new eco_jobs();
$lea_jobs   = new lea_jobs();
$stockcode  = new stockcode();

$row  = $reason->someReason($last, $amount);
$rows = $reason->allReason();

foreach ($row as $key => $value) {
    $id             = $value['per_id'];
    $list['per_id'] = $id;
    //丑闻原由
    $list['reason'] = $value['reason'];
    //校友名
    $persons_row  = $persons->findall_by_id($id);
    $list['name'] = $persons_row['name'];
    //校友政界职位
    $job_id = $pol_perjob->find_by_perid($id);
    if (!empty($job_id)) {
        $list['job'] = array();
        foreach ($job_id as $key1 => $value1) {
            $job           = $pol_jobs->find_by_id($value1['job_id']);
            $list['job'][] = $job['name'];
        }
    }
    //校友学界职位
    $job_id = $lea_perjob->find_by_perid($id);
    if (!empty($job_id)) {
        foreach ($job_id as $key2 => $value2) {
            $job           = $lea_jobs->find_by_id($value2['job_id']);
            $list['job'][] = $job['name'];
        }
    }
    //校友商界职位
    $job_id = $eco_perjob->find_by_perid($id);
    if (!empty($job_id)) {
        foreach ($job_id as $key3 => $value3) {
            $job           = $eco_jobs->find_by_id($value3['job_id']);
            $com           = $stockcode->find_by_id($value3['com_id']);
            $list['job'][] = $com['name'] . $job['name'];
        }
    }
    $lists[] = $list;
}
$lists['count'] = count($rows);
if (!empty($lists)) {
    echo json_encode($lists);
}
