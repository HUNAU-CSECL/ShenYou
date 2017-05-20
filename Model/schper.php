<?php
class schper
{
    //查询schper依据per_id
    public function find_by_perid($num)
    {
        require_once Root_Path . "/Model/mysql.php";
        $db     = new MySQL("127.0.0.1", "root", "", "alumnus");
        $sql    = "SELECT * FROM `schper` WHERE per_id=" . $num;
        $result = $db->query($sql);
        return $db->allAssoc($result);
        $db->dbClose();
    }
    //插入schper表
    public function insert($per_id, $sch_id, $type)
    {
        require_once Root_Path . "/Model/mysql.php";
        $db  = new MySQL("127.0.0.1", "root", "", "alumnus");
        $sql = "INSERT INTO `schper`(`per_id`, `sch_id`,`type`) VALUES ('" . $per_id . "','" . $sch_id . "','" . $type . "')";
        $db->query($sql);
        $db->dbClose();
    }
    //查询schper依据sch_id
    public function find_by_schid($num)
    {
        require_once Root_Path . "/Model/mysql.php";
        $db     = new MySQL("127.0.0.1", "root", "", "alumnus");
        $sql    = "SELECT * FROM `schper` WHERE sch_id=" . $num;
        $result = $db->query($sql);
        return $db->allAssoc($result);
        $db->dbClose();
    }
}
