<?php
class eco_perjob
{
    //查eco_perjob表依据per_job与job_id
    public function find_by_perJobid($per_id, $job_id)
    {
        require_once Root_Path . "/Model/mysql.php";
        $db     = new MySQL("127.0.0.1", "root", "", "alumnus");
        $sql    = "SELECT * FROM `eco_perjob` WHERE per_id=" . $per_id . " AND job_id=" . $job_id;
        $result = $db->query($sql);
        return $db->assoc($result);
        $db->dbClose();
    }
    //插入eco_perjob表
    public function insert($per_id, $com_id, $job_id)
    {
        require_once Root_Path . "/Model/mysql.php";
        $db  = new MySQL("127.0.0.1", "root", "", "alumnus");
        $sql = "INSERT INTO `eco_perjob`(`per_id`, `com_id`,`job_id`) VALUES ('" . $per_id . "','" . $com_id . "','" . $job_id . "')";
        $db->query($sql);
        $db->dbClose();
    }
    //查eco_perjob表依据job_id
    public function find_by_jobid($job_id)
    {
        require_once Root_Path . "/Model/mysql.php";
        $db     = new MySQL("127.0.0.1", "root", "", "alumnus");
        $sql    = "SELECT * FROM `eco_perjob` WHERE job_id=" . $job_id;
        $result = $db->query($sql);
        return $db->allAssoc($result);
        $db->dbClose();
    }
    //插入eco_perjob表grade
    public function insertGrade($id, $grade)
    {
        require_once Root_Path . "/Model/mysql.php";
        $db  = new MySQL("127.0.0.1", "root", "", "alumnus");
        $sql = "UPDATE `eco_perjob` SET `grade`=" . $grade . " WHERE `id`=" . $id;
        $db->query($sql);
        $db->dbClose();
    }
    //查eco_perjob表依据per_job
    public function find_by_perid($per_id)
    {
        require_once Root_Path . "/Model/mysql.php";
        $db     = new MySQL("127.0.0.1", "root", "", "alumnus");
        $sql    = "SELECT * FROM `eco_perjob` WHERE per_id=" . $per_id;
        $result = $db->query($sql);
        return $db->allAssoc($result);
        $db->dbClose();
    }
    //限制查eco_perjob表依据per_job
    public function limitFind_by_perid($per_id)
    {
        require_once Root_Path . "/Model/mysql.php";
        $db     = new MySQL("127.0.0.1", "root", "", "alumnus");
        $sql    = "SELECT * FROM `eco_perjob` WHERE per_id=" . $per_id." ORDER BY grade ASC LIMIT 1";
        $result = $db->query($sql);
        return $db->assoc($result);
        $db->dbClose();
    }
    //查eco_perjob表依据per_job
    public function find_all_perid($per_id)
    {
        require_once Root_Path . "/Model/mysql.php";
        $db     = new MySQL("127.0.0.1", "root", "", "alumnus");
        $sql    = "SELECT * FROM `eco_perjob` WHERE per_id=" . $per_id." ORDER BY grade";
        $result = $db->query($sql);
        return $db->allAssoc($result);
        $db->dbClose();
    }
}