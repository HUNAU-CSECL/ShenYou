<?php
class pol_perjob
{
    //查pol_perjob表依据per_job与job_id
    public function find_by_perJobid($per_id, $job_id)
    {
        require_once Root_Path . "/Model/mysql.php";
        $db     = new MySQL("127.0.0.1", "root", "", "alumnus");
        $sql    = "SELECT * FROM `pol_perjob` WHERE per_id=" . $per_id . " AND job_id=" . $job_id;
        $result = $db->query($sql);
        return $db->assoc($result);
        $db->dbClose();
    }
    //插入pol_perjob表
    public function insert($per_id, $job_id)
    {
        require_once Root_Path . "/Model/mysql.php";
        $db  = new MySQL("127.0.0.1", "root", "", "alumnus");
        $sql = "INSERT INTO `pol_perjob`(`per_id`, `job_id`) VALUES ('" . $per_id . "','" . $job_id . "')";
        $db->query($sql);
        $db->dbClose();
    }
    //查pol_perjob表依据per_job
    public function find_by_perid($per_id)
    {
        require_once Root_Path . "/Model/mysql.php";
        $db     = new MySQL("127.0.0.1", "root", "", "alumnus");
        $sql    = "SELECT * FROM `pol_perjob` WHERE per_id=" . $per_id;
        $result = $db->query($sql);
        return $db->allAssoc($result);
        $db->dbClose();
    }
}
