<?php
class old
{
    //查old依据校名
    public function find_by_name($string)
    {
        require_once Root_Path . "/Model/mysql.php";
        $db     = new MySQL("127.0.0.1", "root", "", "alumnus");
        $sql    = "SELECT * FROM `old` WHERE name='" . $string . "' AND date_state = 1";
        $result = $db->query($sql);
        return $db->assoc($result);
        $db->dbClose();
    }
    //查old不带now_id的曾用名
    public function find_noNowID()
    {
        require_once Root_Path . "/Model/mysql.php";
        $db     = new MySQL("127.0.0.1", "root", "", "alumnus");
        $sql    = "SELECT * FROM `old` WHERE  `now_id` is null";
        $result = $db->query($sql);
        return $db->allAssoc($result);
        $db->dbClose();
    }
    //插入schper表（不带now_id）
    public function insert($string)
    {
        require_once Root_Path . "/Model/mysql.php";
        $db  = new MySQL("127.0.0.1", "root", "", "alumnus");
        $sql = "INSERT INTO `old`(`name`) VALUES ('" . $string . "')";
        $db->query($sql);
        $db->dbClose();
    }
    //查old全部高校名
    public function allName()
    {
        require_once Root_Path . "/Model/mysql.php";
        $db     = new MySQL("127.0.0.1", "root", "", "alumnus");
        $sql    = "SELECT name FROM `old` WHERE date_state=1";
        $result = $db->query($sql);
        return $db->allAssoc($result);
        $db->dbClose();
    }
    //查old未匹配now_id的曾用名
    public function nullName($num1, $num2)
    {
        require_once Root_Path . "/Model/mysql.php";
        $db     = new MySQL("127.0.0.1", "root", "", "alumnus");
        $sql    = "SELECT * FROM `old` WHERE `now_id` is null AND date_state=1 ORDER BY id ASC LIMIT $num1,$num2";
        $result = $db->query($sql);
        return $db->allAssoc($result);
        $db->dbClose();
    }
    //查old未匹配now_id的全部曾用名
    public function allNullName()
    {
        require_once Root_Path . "/Model/mysql.php";
        $db     = new MySQL("127.0.0.1", "root", "", "alumnus");
        $sql    = "SELECT * FROM `old` WHERE `now_id` is null AND date_state=1";
        $result = $db->query($sql);
        return $db->allAssoc($result);
        $db->dbClose();
    }
    //更old的now_id$string:曾用名,$num:now_id
    public function upold($string, $num)
    {
        require_once Root_Path . "/Model/mysql.php";
        $db     = new MySQL("127.0.0.1", "root", "", "alumnus");
        $sql    = "UPDATE `old` SET `now_id`=$num WHERE name='" . $string . "' AND date_state=1";
        $result = $db->query($sql);
        $db->dbClose();
    }
    //取消更old的now_id$string:曾用名
    public function cancelold($string)
    {
        require_once Root_Path . "/Model/mysql.php";
        $db     = new MySQL("127.0.0.1", "root", "", "alumnus");
        $sql    = "UPDATE `old` SET `date_state`=0 WHERE name='" . $string . "'";
        $result = $db->query($sql);
        $db->dbClose();
    }
}
