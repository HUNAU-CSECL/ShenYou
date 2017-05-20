<?php
class schcare
{
	//查schcare依据用户ID与高校ID当前是否已经关注
    public function isCare($user_id,$sch_id)
    {
        require_once Root_Path . "/Model/mysql.php";
        $db     = new MySQL("127.0.0.1", "root", "", "alumnus");
        $sql    = "SELECT * FROM `schcare` WHERE user_id=$user_id AND sch_id=$sch_id AND data_state = 1";
        $result = $db->query($sql);
        return $db->allAssoc($result);
        $db->dbClose();
    }
    //查schcare依据用户ID与高校ID之前是否关注过
    public function isCared($user_id,$sch_id)
    {
        require_once Root_Path . "/Model/mysql.php";
        $db     = new MySQL("127.0.0.1", "root", "", "alumnus");
        $sql    = "SELECT * FROM `schcare` WHERE user_id=$user_id AND sch_id=$sch_id";
        $result = $db->query($sql);
        return $db->allAssoc($result);
        $db->dbClose();
    }
    //更schcare的data为1$user_id用户IDsch_id高校ID
    public function upCare1($user_id,$sch_id)
    {
        require_once Root_Path . "/Model/mysql.php";
        $db     = new MySQL("127.0.0.1", "root", "", "alumnus");
        $sql    = "UPDATE `schcare` SET `data_state`=1 WHERE user_id=$user_id AND sch_id=$sch_id";
        $result = $db->query($sql);
        $db->dbClose();
    }
    //更schcare的data为0$user_id用户IDsch_id高校ID
    public function upCare0($user_id,$sch_id)
    {
        require_once Root_Path . "/Model/mysql.php";
        $db     = new MySQL("127.0.0.1", "root", "", "alumnus");
        $sql    = "UPDATE `schcare` SET `data_state`=0 WHERE user_id=$user_id AND sch_id=$sch_id";
        $result = $db->query($sql);
        $db->dbClose();
    }
    //插入schcare表$user_id用户IDsch_id高校ID
    public function insert($user_id,$sch_id)
    {
        require_once Root_Path . "/Model/mysql.php";
        $db  = new MySQL("127.0.0.1", "root", "", "alumnus");
        $sql = "INSERT INTO `schcare`(`user_id`,`sch_id`) VALUES ($user_id,$sch_id)";
        $db->query($sql);
        $db->dbClose();
    }
    //分页加载更多
    public function moreSchCare($num1, $num2,$num3)
    {
        require_once Root_Path . "/Model/mysql.php";
        $db     = new MySQL("127.0.0.1", "root", "", "alumnus");
        $sql    = "SELECT * FROM `schcare` WHERE `user_id`=$num1 AND data_state = 1 ORDER BY update_at DESC LIMIT $num2,$num3";
        $result = $db->query($sql);
        return $db->allAssoc($result);
        $db->dbClose();
    }
    //全部关注的高校
    public function allSchCare($num)
    {
        require_once Root_Path . "/Model/mysql.php";
        $db     = new MySQL("127.0.0.1", "root", "", "alumnus");
        $sql    = "SELECT * FROM `schcare` WHERE `user_id`=$num AND data_state = 1";
        $result = $db->query($sql);
        return $db->allAssoc($result);
        $db->dbClose();
    }
}
