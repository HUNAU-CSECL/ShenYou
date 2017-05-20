<?php
class school
{
    //查school依据校名
    public function find_by_name($string)
    {
        require_once Root_Path . "/Model/mysql.php";
        $db     = new MySQL("127.0.0.1", "root", "", "alumnus");
        $sql    = "SELECT * FROM `school` WHERE name='" . $string . "' AND date_state = 1";
        $result = $db->query($sql);
        return $db->assoc($result);
        $db->dbClose();
    }
    //查school依据关键词
    public function find_by_keyword($string)
    {
        require_once Root_Path . "/Model/mysql.php";
        $db     = new MySQL("127.0.0.1", "root", "", "alumnus");
        $sql    = "SELECT * FROM `school` WHERE name LIKE '%" . $string . "%' AND date_state = 1 ORDER BY id ASC LIMIT 5";
        $result = $db->query($sql);
        return $db->allAssoc($result);
        $db->dbClose();
    }
    //查school全部高校名
    public function allName()
    {
        require_once Root_Path . "/Model/mysql.php";
        $db     = new MySQL("127.0.0.1", "root", "", "alumnus");
        $sql    = "SELECT `name` FROM `school` WHERE date_state=1";
        $result = $db->query($sql);
        return $db->allAssoc($result);
        $db->dbClose();
    }
    //查school依据省份ID$num
    public function find_by_proid($num)
    {
        require_once Root_Path . "/Model/mysql.php";
        $db     = new MySQL("127.0.0.1", "root", "", "alumnus");
        $sql    = "SELECT * FROM `school` WHERE pro_id=$num AND date_state = 1";
        $result = $db->query($sql);
        return $db->allAssoc($result);
        $db->dbClose();
    }
    //查school依据ID$num
    public function find_by_id($num)
    {
        require_once Root_Path . "/Model/mysql.php";
        $db     = new MySQL("127.0.0.1", "root", "", "alumnus");
        $sql    = "SELECT * FROM `school` WHERE id=$num AND date_state = 1";
        $result = $db->query($sql);
        return $db->assoc($result);
        $db->dbClose();
    }
    //查school依据id数组
    public function find_all_arrId($array)
    {
        require_once Root_Path . "/Model/mysql.php";
        $db     = new MySQL("127.0.0.1", "root", "", "alumnus");
        $minSQL = '';
        foreach ($array as $key => $value) {
            $minSQL .= $value . ",";
        }
        $minSQL = substr($minSQL, 0, strlen($minSQL) - 1);
        $sql = "SELECT * FROM `school` WHERE id IN (" . $minSQL . ") AND date_state = 1";
        $result = $db->query($sql);
        return $db->allAssoc($result);
        $db->dbClose();
    }
}
