<?php
//加载一个高校校友
define('Root_Path', preg_replace('/\\\Frontend\\\back/', '', dirname(__FILE__)));
require_once Root_Path . '/Model/school.php';
require_once Root_Path . '/Model/schper.php';
require_once Root_Path . '/Model/persons.php';
require_once Root_Path . '/Model/pol_perjob.php';
require_once Root_Path . '/Model/eco_perjob.php';
require_once Root_Path . '/Model/lea_perjob.php';
require_once Root_Path . '/Model/pol_jobs.php';
require_once Root_Path . '/Model/lea_jobs.php';
require_once Root_Path . '/Model/eco_jobs.php';
require_once Root_Path . '/Model/stockcode.php';

$rows = array();
$name = urldecode($_POST['name']);

$persons    = new persons();
$pol_perjob = new pol_perjob();
$eco_perjob = new eco_perjob();
$lea_perjob = new lea_perjob();
$pol_jobs   = new pol_jobs();
$lea_jobs   = new lea_jobs();
$eco_jobs   = new eco_jobs();
$stockcode  = new stockcode();

//高校ID
$school      = new school();
$row         = $school->find_by_name($name);
$sch_id      = $row['id'];
$list['pol'] = array();
$list['eco'] = array();
$list['lea'] = array();
//各界校友ID
$schper = new schper();
$rows   = $schper->find_by_schid($sch_id);

foreach ($rows as $key => $value) {
    $perid = $value;
    //校友姓名与图像
    $info = $persons->find_by_id($perid['per_id']);
    if (!empty($info)) {
        $per_id = $info['id'];
        $name   = $info['name'];
        $img    = $info['photo'];
        $intr   = $info['intr'];
        $time   = $info['update_at'];
    } else {
        continue;
    }

    if ($perid['type'] == 1) {
//政界
        //职位ID
        $jobs = $pol_perjob->find_by_perid($perid['per_id']);
        if (!empty($jobs)) {
            $jobs_id = array();
            foreach ($jobs as $key => $value) {
                $jobs_id[] = $value['job_id'];
            }
            //职位名称与等级
            $job = $pol_jobs->find_by_arrId($jobs_id, 1);

            $job_name = $job['name'];
            if ($job['grade']) {
                $grade = $job['grade'];
            } else {
                $grade = 12;
            }
        } else {
            $job_name = '';
            $grade    = 12;
        }
        $grade = (12 - $grade) * 10 / 11;
        if ($grade == 0) {
            $grade = '';
        } else {
            $grade = sprintf("%.2f", $grade);
        }
        if ($img == '') {
            $img = '';
        }
        if ($grade == 10.00) {
            $grade = 9.99;
        }
        $list['pol'][] = [
            'per_id' => $per_id,
            'name'   => $name,
            'img'    => $img,
            'intr'   => $intr,
            'job'    => $job_name,
            'grade'  => $grade,
            'time'   => $time,
            'type'   => 1,
        ];
    } elseif ($perid['type'] == 2) {
//商界
        //职位ID
        $jobs = $eco_perjob->limitFind_by_perid($perid['per_id']);
        if (!empty($jobs)) {
            //影响力
            if ($jobs['grade']) {
                $grade = $jobs['grade'];
            } else {
                $grade = 0;
            }
            //任职公司名称
            $com      = $stockcode->find_by_id($jobs['com_id']);
            $com_name = $com['name'];
            //职位名称
            $job      = $eco_jobs->find_by_id($jobs['job_id']);
            $job_name = $job['name'];
        } else {
            $job_name = '';
            $com_name = '';
            $grade    = 0;
        }
        $grade = $grade * 10 / 52;
        if ($grade == 0) {
            $grade = '';
        } else {
            $grade = sprintf("%.2f", $grade);
        }
        if ($img == '') {
            $img = '';
        }
        $list['eco'][] = [
            'per_id' => $per_id,
            'name'   => $name,
            'img'    => $img,
            'intr'   => $intr,
            'job'    => $com_name . $job_name,
            'grade'  => $grade,
            'time'   => $time,
            'type'   => 2,
        ];
    } else {
//学界
        //职位ID
        $jobs = $lea_perjob->find_by_perid($perid['per_id']);
        if (!empty($jobs)) {
            $jobs_id = array();
            foreach ($jobs as $key => $value) {
                $jobs_id[] = $value['job_id'];
            }
            //职位名称与等级
            $job      = $lea_jobs->find_by_arrId($jobs_id, 1);
            $job_name = $job['name'];
            if ($job['grade']) {
                $grade = $job['grade'];
            } else {
                $grade = 11;
            }
        } else {
            $job_name = '';
            $grade    = 11;
        }
        $grade = 10.5 - 0.5 * $grade;
        if ($grade == 0) {
            $grade = '';
        } else {
            $grade = sprintf("%.2f", $grade);
        }
        if ($img == '') {
            $img = '';
        } elseif (preg_match('/casad\.cas\.cn\:80/', $img)) {
            $img = '';
        }
        $list['lea'][] = [
            'per_id' => $per_id,
            'name'   => $name,
            'img'    => $img,
            'intr'   => $intr,
            'job'    => $job_name,
            'grade'  => $grade,
            'time'   => $time,
            'type'   => 3,
        ];
    }
}

// echo "<pre>";
// print_r($list);
echo json_encode($list);
