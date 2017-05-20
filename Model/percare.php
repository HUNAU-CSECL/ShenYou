<?php
class percare
{
    //查percare依据用户ID与校友ID当前是否已经关注
    public function isCare($user_id, $per_id)
    {
        require_once Root_Path . "/Model/mysql.php";
        $db     = new MySQL("127.0.0.1", "root", "", "alumnus");
        $sql    = "SELECT * FROM `percare` WHERE user_id=$user_id AND per_id=$per_id AND data_state = 1";
        $result = $db->query($sql);
        return $db->allAssoc($result);
        $db->dbClose();
    }
    //查percare依据用户ID与校友ID之前是否关注过
    public function isCared($user_id,$per_id)
    {
        require_once Root_Path . "/Model/mysql.php";
        $db     = new MySQL("127.0.0.1", "root", "", "alumnus");
        $sql    = "SELECT * FROM `percare` WHERE user_id=$user_id AND per_id=$per_id";
        $result = $db->query($sql);
        return $db->allAssoc($result);
        $db->dbClose();
    }
    //更percare的data为1$user_id用户IDper_id校友ID
    public function upCare1($user_id,$per_id)
    {
        require_once Root_Path . "/Model/mysql.php";
        $db     = new MySQL("127.0.0.1", "root", "", "alumnus");
        $sql    = "UPDATE `percare` SET `data_state`=1 WHERE user_id=$user_id AND per_id=$per_id";
        $result = $db->query($sql);
        $db->dbClose();
    }
    //更percare的data为0$user_id用户IDper_id校友ID
    public function upCare0($user_id,$per_id)
    {
        require_once Root_Path . "/Model/mysql.php";
        $db     = new MySQL("127.0.0.1", "root", "", "alumnus");
        $sql    = "UPDATE `percare` SET `data_state`=0 WHERE user_id=$user_id AND per_id=$per_id";
        $result = $db->query($sql);
        $db->dbClose();
    }
    //插入percare表$user_id用户IDper_id校友ID
    public function insert($user_id,$per_id)
    {
        require_once Root_Path . "/Model/mysql.php";
        $db  = new MySQL("127.0.0.1", "root", "", "alumnus");
        $sql = "INSERT INTO `percare`(`user_id`,`per_id`) VALUES ($user_id,$per_id)";
        $db->query($sql);
        $db->dbClose();
    }
    //分页加载更多
    public function morePerCare($num1, $num2,$num3)
    {
        require_once Root_Path . "/Model/mysql.php";
        $db     = new MySQL("127.0.0.1", "root", "", "alumnus");
        $sql    = "SELECT * FROM `percare` WHERE `user_id`=$num1 AND data_state = 1 ORDER BY update_at DESC LIMIT $num2,$num3";
        $result = $db->query($sql);
        return $db->allAssoc($result);
        $db->dbClose();
    }
    //全部关注的校友
    public function allPerCare($num)
    {
        require_once Root_Path . "/Model/mysql.php";
        $db     = new MySQL("127.0.0.1", "root", "", "alumnus");
        $sql    = "SELECT * FROM `percare` WHERE `user_id`=$num AND data_state = 1";
        $result = $db->query($sql);
        return $db->allAssoc($result);
        $db->dbClose();
    }
}
