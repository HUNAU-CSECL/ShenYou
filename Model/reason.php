<?php
class reason
{
    //插入reason表$num:人的ID$string:原由内容
    public function insert($num, $string)
    {
        require_once Root_Path . "/Model/mysql.php";
        $db  = new MySQL("127.0.0.1", "root", "", "alumnus");
        $sql = "INSERT INTO `reason`(`per_id`,`reason`) VALUES (" . $num . ",'" . $string . "')";
        $db->query($sql);
        $db->dbClose();
    }
    //查reason丑闻
    public function someReason($num1, $num2)
    {
        require_once Root_Path . "/Model/mysql.php";
        $db     = new MySQL("127.0.0.1", "root", "", "alumnus");
        $sql    = "SELECT * FROM `reason` WHERE data_state=1 ORDER BY id DESC LIMIT $num1,$num2";
        $result = $db->query($sql);
        return $db->allAssoc($result);
        $db->dbClose();
    }
    //查reason所有丑闻
    public function allReason()
    {
        require_once Root_Path . "/Model/mysql.php";
        $db     = new MySQL("127.0.0.1", "root", "", "alumnus");
        $sql    = "SELECT * FROM `reason` WHERE data_state=1";
        $result = $db->query($sql);
        return $db->allAssoc($result);
        $db->dbClose();
    }
    //更reason撤销丑闻$num:校友ID
    public function cancel($num)
    {
        require_once Root_Path . "/Model/mysql.php";
        $db     = new MySQL("127.0.0.1", "root", "", "alumnus");
        $sql    = "UPDATE `reason` SET `data_state`=0 WHERE per_id=$num";
        $result = $db->query($sql);
        $db->dbClose();
    }
}
