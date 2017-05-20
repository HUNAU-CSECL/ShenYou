<?php

class eco_jobs
{
    //查eco_jobs依据职位
    public function find_by_name($string)
    {
        require_once Root_Path . "/Model/mysql.php";
        $db     = new MySQL("127.0.0.1", "root", "", "alumnus");
        $sql    = "SELECT * FROM `eco_jobs` WHERE name='" . $string . "'";
        $result = $db->query($sql);
        return $db->assoc($result);
        $db->dbClose();
    }
    //插入eco_jobs表
    public function insert($string)
    {
        require_once Root_Path . "/Model/mysql.php";
        $db  = new MySQL("127.0.0.1", "root", "", "alumnus");
        $sql = "INSERT INTO `eco_jobs`(`name`) VALUES ('" . $string . "')";
        $db->query($sql);
        $db->dbClose();
    }
    //插入eco_jobs表的grade
    public function insertGrade($string, $num)
    {
        require_once Root_Path . "/Model/mysql.php";
        $db  = new MySQL("127.0.0.1", "root", "", "alumnus");
        $sql = "UPDATE `eco_jobs` SET `grade`=" . $num . " WHERE `name`='" . $string . "'";
        $db->query($sql);
        $db->dbClose();
    }
    //插入eco_jobs表的id
    public function insertId()
    {
        require_once Root_Path . "/Model/mysql.php";
        $db     = new MySQL("127.0.0.1", "root", "", "alumnus");
        $sql    = "SELECT max(id) from `eco_jobs`";
        $result = $db->query($sql);
        $array  = $db->assoc($result);
        return $array['max(id)'];
        $db->dbClose();
    }
    //查eco_jobs表选出等级为空项
    public function nullGrade($num1, $num2)
    {
        require_once Root_Path . "/Model/mysql.php";
        $db     = new MySQL("127.0.0.1", "root", "", "alumnus");
        $sql    = "SELECT * FROM `eco_jobs` WHERE  `grade` is null ORDER BY id ASC LIMIT $num1,$num2";
        $result = $db->query($sql);
        return $db->allAssoc($result);
        $db->dbClose();
    }
    //查eco_jobs表选出全部等级为空项
    public function allNullGrade()
    {
        require_once Root_Path . "/Model/mysql.php";
        $db     = new MySQL("127.0.0.1", "root", "", "alumnus");
        $sql    = "SELECT * FROM `eco_jobs` WHERE  `grade` is null";
        $result = $db->query($sql);
        return $db->allAssoc($result);
        $db->dbClose();
    }
    //查eco_jobs依据id
    public function find_by_id($num)
    {
        require_once Root_Path . "/Model/mysql.php";
        $db     = new MySQL("127.0.0.1", "root", "", "alumnus");
        $sql    = "SELECT * FROM `eco_jobs` WHERE id='" . $num . "'";
        $result = $db->query($sql);
        return $db->assoc($result);
        $db->dbClose();
    }
}
