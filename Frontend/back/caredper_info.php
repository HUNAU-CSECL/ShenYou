<?php
//加载被关注校友的信息
define('Root_Path', preg_replace('/\\\Frontend\\\back/', '', dirname(__FILE__)));

require_once Root_Path . '/Model/persons.php';
require_once Root_Path . '/Model/pol_perjob.php';
require_once Root_Path . '/Model/eco_perjob.php';
require_once Root_Path . '/Model/lea_perjob.php';
require_once Root_Path . '/Model/pol_jobs.php';
require_once Root_Path . '/Model/lea_jobs.php';
require_once Root_Path . '/Model/eco_jobs.php';
require_once Root_Path . '/Model/stockcode.php';

$id = $_POST['id'];

$persons    = new persons();
$pol_perjob = new pol_perjob();
$eco_perjob = new eco_perjob();
$lea_perjob = new lea_perjob();

$pol_perjob_rows = $pol_perjob->find_by_perid($id);
$eco_perjob_rows = $eco_perjob->find_by_perid($id);
$lea_perjob_rows = $lea_perjob->find_by_perid($id);

if (!empty($pol_perjob_rows)) {
    foreach ($pol_perjob_rows as $key => $value) {
        $jobs_id[] = $value['job_id'];
    }
    $pol_jobs = new pol_jobs;
    $job      = $pol_jobs->find_all_arrId($jobs_id);
    foreach ($job as $key => $value) {
        $jobs[] = $value['name'];
    }
}

if (!empty($eco_perjob_rows)) {
    foreach ($eco_perjob_rows as $key => $value) {
        $stockcode = new stockcode();
        $com       = $stockcode->find_by_id($value['com_id']);
        $com_name  = $com['name'];
        $eco_jobs  = new eco_jobs();
        $job       = $eco_jobs->find_by_id($value['job_id']);
        $job_name  = $job['name'];
        $jobs[]    = $com_name . $job_name;
    }
}

if (!empty($lea_perjob_rows)) {
    foreach ($lea_perjob_rows as $key => $value) {
        $jobs_id[] = $value['job_id'];
    }
    $lea_jobs = new lea_jobs;
    $job      = $lea_jobs->find_all_arrId($jobs_id);
    foreach ($job as $key => $value) {
        $jobs[] = $value['name'];
    }
}

$persons_row = $persons->find_by_id($id);
$list[]=[
	'name'=>$persons_row['name'],
	'jobs'=>$jobs,
	'intr'=>$persons_row['intr'],
	'time'=>$persons_row['update_at'],
];

echo json_encode($list);