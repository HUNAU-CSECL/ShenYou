<?php
//加载一个高校校友多个职位与用户是否关注状态
define('Root_Path', preg_replace('/\\\Frontend\\\back/', '', dirname(__FILE__)));

require_once Root_Path . '/Model/pol_perjob.php';
require_once Root_Path . '/Model/eco_perjob.php';
require_once Root_Path . '/Model/lea_perjob.php';
require_once Root_Path . '/Model/pol_jobs.php';
require_once Root_Path . '/Model/lea_jobs.php';
require_once Root_Path . '/Model/eco_jobs.php';
require_once Root_Path . '/Model/stockcode.php';
require_once Root_Path . '/Model/percare.php';

$type    = $_POST['type'];
$per_id  = $_POST['per_id'];
$user_id = $_POST['user_id'];

$jobs = array();
$list = array();

if ($type == 1) {
    $pol_perjob  = new pol_perjob();
    $perjob_rows = $pol_perjob->find_by_perid($per_id);
    if (!empty($perjob_rows)) {
        $jobs_id = array();
        foreach ($perjob_rows as $key => $value) {
            $jobs_id[] = $value['job_id'];
        }
        $pol_jobs = new pol_jobs;
        $job      = $pol_jobs->find_all_arrId($jobs_id);
        foreach ($job as $key => $value) {
            $jobs[] = $value['name'];
        }
    }
} elseif ($type == 2) {
    $eco_perjob  = new eco_perjob();
    $ecojob_rows = $eco_perjob->find_all_perid($per_id);
    foreach ($ecojob_rows as $key => $value) {
        $stockcode = new stockcode();
        $com       = $stockcode->find_by_id($value['com_id']);
        $com_name  = $com['name'];
        $eco_jobs  = new eco_jobs();
        $job      = $eco_jobs->find_by_id($value['job_id']);
        $job_name  = $job['name'];
        $jobs[]    = $com_name . $job_name;
    }
} else {
    $lea_perjob  = new lea_perjob();
    $leajob_rows = $lea_perjob->find_by_perid($per_id);
    if (!empty($leajob_rows)) {
        $jobs_id = array();
        foreach ($leajob_rows as $key => $value) {
            $jobs_id[] = $value['job_id'];
        }
        $lea_jobs = new lea_jobs();
        $job      = $lea_jobs->find_all_arrId($jobs_id);
        foreach ($job as $key => $value) {
            $jobs[] = $value['name'];
        }
    }
}

if ($user_id !== '') {
    $percare = new percare();
    $care    = $percare->isCare($user_id, $per_id);
    if (empty($care)) {
        $care = 0;
    } else {
        $care = 1;
    }
} else {
    $care = 0;
}

$list = [
    'jobs' => $jobs,
    'care' => $care,
];

echo json_encode($list);
