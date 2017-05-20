<?php
define('Root_Path', preg_replace('/\\\Backend\\\back/', '', dirname(__FILE__)));
require Root_Path . '/Model/persons.php';
require Root_Path . '/Model/pol_perjob.php';
require Root_Path . '/Model/eco_perjob.php';
require Root_Path . '/Model/lea_perjob.php';
require Root_Path . '/Model/pol_jobs.php';
require Root_Path . '/Model/eco_jobs.php';
require Root_Path . '/Model/lea_jobs.php';
require Root_Path . '/Model/stockcode.php';
$list=array();
$name = $_POST['name'];

$persons    = new persons();
$pol_perjob = new pol_perjob();
$eco_perjob = new eco_perjob();
$lea_perjob   = new lea_perjob();
$pol_jobs   = new pol_jobs();
$eco_jobs   = new eco_jobs();
$lea_jobs   = new lea_jobs();
$stockcode = new stockcode();

$rows = $persons->find_by_name(trim($name));

if (!empty($rows)) {
    foreach ($rows as $key => $value) {
        $id = $value['id'];
        //政界职位
        $job_id = $pol_perjob->find_by_perid($value['id']);
        if (!empty($job_id)) {
            foreach ($job_id as $key1 => $value1) {
                $job      = $pol_jobs->find_by_id($value1['job_id']);
                $list[$id][] = $job['name'];
            }
        }
        //学界职位
        $job_id = $lea_perjob->find_by_perid($value['id']);
        if (!empty($job_id)) {
            foreach ($job_id as $key2 => $value2) {
                $job      = $lea_jobs->find_by_id($value2['job_id']);
                $list[$id][] = $job['name'];
            }
        }
        //商界职位
        $job_id = $eco_perjob->find_by_perid($value['id']);
        if (!empty($job_id)) {
            foreach ($job_id as $key3 => $value3) {
                $job      = $eco_jobs->find_by_id($value3['job_id']);
                $com      = $stockcode->find_by_id($value3['com_id']);
                $list[$id][] = $com['name'] . $job['name'];
            }
        }
    }
}
echo json_encode($list);
