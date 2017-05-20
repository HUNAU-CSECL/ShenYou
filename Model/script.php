<?php
class script
{
    //查script依据关键词
    public function findPol_by_keyword($string)
    {
        require_once Root_Path . "/Model/mysql.php";
        $db     = new MySQL("127.0.0.1", "root", "", "alumnus");
        $sql    = "SELECT * FROM `script` WHERE name LIKE '%" . $string . "%' AND type=1 AND data_state = 1 order by rand()";
        $result = $db->query($sql);
        return $db->allAssoc($result);
        $db->dbClose();
    }
    //政界加载更多$num3为类型1为政界2为商界3为学界
    public function moreScript($num1, $num2,$num3)
    {
        require_once Root_Path . "/Model/mysql.php";
        $db     = new MySQL("127.0.0.1", "root", "", "alumnus");
        $sql    = "SELECT * FROM `script` WHERE `type`=$num3 ORDER BY id ASC LIMIT $num1,$num2";
        $result = $db->query($sql);
        return $db->allAssoc($result);
        $db->dbClose();
    }
    //政界全部$num为类型1为政界2为商界3为学界
    public function allScript($num)
    {
        require_once Root_Path . "/Model/mysql.php";
        $db     = new MySQL("127.0.0.1", "root", "", "alumnus");
        $sql    = "SELECT * FROM `script` WHERE `type`=$num";
        $result = $db->query($sql);
        return $db->allAssoc($result);
        $db->dbClose();
    }
}
