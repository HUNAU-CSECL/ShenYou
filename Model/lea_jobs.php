<?php

class lea_jobs
{
    //查lea_jobs依据职位
    public function find_by_name($string)
    {
        require_once Root_Path . "/Model/mysql.php";
        $db     = new MySQL("127.0.0.1", "root", "", "alumnus");
        $sql    = "SELECT * FROM `lea_jobs` WHERE name='" . $string . "'";
        $result = $db->query($sql);
        return $db->assoc($result);
        $db->dbClose();
    }
    //插入lea_jobs表
    public function insert($string)
    {
        require_once Root_Path . "/Model/mysql.php";
        $db  = new MySQL("127.0.0.1", "root", "", "alumnus");
        $sql = "INSERT INTO `lea_jobs`(`name`) VALUES ('" . $string . "')";
        $db->query($sql);
        $db->dbClose();
    }
    //插入lea_jobs表的grade
    public function insertGrade($string, $num)
    {
        require_once Root_Path . "/Model/mysql.php";
        $db  = new MySQL("127.0.0.1", "root", "", "alumnus");
        $sql = "UPDATE `lea_jobs` SET `grade`=" . $num . " WHERE `name`='" . $string . "'";
        $db->query($sql);
        $db->dbClose();
    }
    //插入lea_jobs表的id
    public function insertId()
    {
        require_once Root_Path . "/Model/mysql.php";
        $db     = new MySQL("127.0.0.1", "root", "", "alumnus");
        $sql    = "SELECT max(id) from `lea_jobs`";
        $result = $db->query($sql);
        $array  = $db->assoc($result);
        return $array['max(id)'];
        $db->dbClose();
    }
    //查lea_jobs表选出等级为空项
    public function allGrade($num1, $num2)
    {
        require_once Root_Path . "/Model/mysql.php";
        $db     = new MySQL("127.0.0.1", "root", "", "alumnus");
        $sql    = "SELECT * FROM `lea_jobs` ORDER BY id ASC LIMIT $num1,$num2";
        $result = $db->query($sql);
        return $db->allAssoc($result);
        $db->dbClose();
    }
    //查lea_jobs依据id
    public function find_by_id($num)
    {
        require_once Root_Path . "/Model/mysql.php";
        $db     = new MySQL("127.0.0.1", "root", "", "alumnus");
        $sql    = "SELECT * FROM `lea_jobs` WHERE id='" . $num . "'";
        $result = $db->query($sql);
        return $db->assoc($result);
        $db->dbClose();
    }
    //限制$num个查lea_jobs依据id数组
    public function find_by_arrId($array, $num)
    {
        require_once Root_Path . "/Model/mysql.php";
        $db     = new MySQL("127.0.0.1", "root", "", "alumnus");
        $minSQL = '';
        foreach ($array as $key => $value) {
            $minSQL .= $value . ",";
        }
        $minSQL = substr($minSQL, 0, strlen($minSQL) - 1);
        $sql    = "SELECT * FROM `lea_jobs` WHERE id IN (" . $minSQL . ") ORDER BY grade ASC LIMIT $num";
        $result = $db->query($sql);
        return $db->assoc($result);
        $db->dbClose();
    }
    //查lea_jobs依据id数组
    public function find_all_arrId($array)
    {
        require_once Root_Path . "/Model/mysql.php";
        $db     = new MySQL("127.0.0.1", "root", "", "alumnus");
        $minSQL = '';
        foreach ($array as $key => $value) {
            $minSQL .= $value . ",";
        }
        $minSQL = substr($minSQL, 0, strlen($minSQL) - 1);
        $sql = "SELECT * FROM `lea_jobs` WHERE id IN (" . $minSQL . ") ORDER BY grade ASC";
        $result = $db->query($sql);
        return $db->allAssoc($result);
        $db->dbClose();
    }
}
