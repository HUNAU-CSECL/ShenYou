<?php
class persons
{
    //查询person表依据姓名
    public function find_by_name($string)
    {
        require_once Root_Path . "/Model/mysql.php";
        $db     = new MySQL("127.0.0.1", "root", "", "alumnus");
        $sql    = "SELECT * FROM `persons` WHERE name='" . $string . "' AND data_state = 1";
        $result = $db->query($sql);
        return $db->allAssoc($result);
        $db->dbClose();
    }
    //查询person表依据id
    public function find_by_id($num)
    {
        require_once Root_Path . "/Model/mysql.php";
        $db     = new MySQL("127.0.0.1", "root", "", "alumnus");
        $sql    = "SELECT * FROM `persons` WHERE id=$num";
        $result = $db->query($sql);
        return $db->assoc($result);
        $db->dbClose();
    }
    //查person依据关键词
    public function find_by_keyword($string)
    {
        require_once Root_Path . "/Model/mysql.php";
        $db     = new MySQL("127.0.0.1", "root", "", "alumnus");
        $sql    = "SELECT * FROM `persons` WHERE name LIKE '%" . $string . "%' AND data_state = 1 ORDER BY id ASC LIMIT 5";
        $result = $db->query($sql);
        return $db->allAssoc($result);
        $db->dbClose();
    }
    //插入persons表
    public function insertPerson($name, $img, $intr)
    {
        require_once Root_Path . "/Model/mysql.php";
        $db = new MySQL("127.0.0.1", "root", "", "alumnus");
        // $sql = "INSERT INTO `persons`(`name`, `photo`, `intr`) VALUES ('" . $name . "','" . $img . "','" . $intr . "')";
        $sql = "INSERT INTO `persons`(`name`, `photo`, `intr`,`update_at`) VALUES ('" . $name . "','" . $img . "','" . $intr . "'," . "now() " . ")";
        $db->query($sql);
        $db->dbClose();
    }
    //插入persons表(除photo)
    public function insertPersonPh($name, $intr)
    {
        require_once Root_Path . "/Model/mysql.php";
        $db = new MySQL("127.0.0.1", "root", "", "alumnus");
        // $sql = "INSERT INTO `persons`(`name`, `photo`, `intr`) VALUES ('" . $name . "','" . $img . "','" . $intr . "')";
        $sql = "INSERT INTO `persons`(`name`,  `intr`,`update_at`) VALUES ('" . $name . "','" . $intr . "'," . "now() " . ")";
        $db->query($sql);
        $db->dbClose();
    }
    //插入persons表的id
    public function insertId()
    {
        require_once Root_Path . "/Model/mysql.php";
        $db     = new MySQL("127.0.0.1", "root", "", "alumnus");
        $sql    = "SELECT max(id) from `persons`";
        $result = $db->query($sql);
        $array  = $db->assoc($result);
        return $array['max(id)'];
        $db->dbClose();
    }
    //更新persons表
    public function update($img, $intr, $num)
    {
        require_once Root_Path . "/Model/mysql.php";
        $db = new MySQL("127.0.0.1", "root", "", "alumnus");
        // $sql = "UPDATE `persons` SET `photo`='" . $img . "',`intr`='" . $intr . "'WHERE id=" . $num;
        $sql = "UPDATE `persons` SET `photo`='" . $img . "',`intr`='" . $intr . "',`update_at`=" . "now()" . "WHERE id=" . $num;
        $db->query($sql);
        $db->dbClose();
    }
    //更新persons表(除photo)
    public function updatePh($intr, $num)
    {
        require_once Root_Path . "/Model/mysql.php";
        $db  = new MySQL("127.0.0.1", "root", "", "alumnus");
        $sql = "UPDATE `persons` SET `intr`='" . $intr . "',`update_at`=" . "now()" . "WHERE id=" . $num;
        $db->query($sql);
        $db->dbClose();
    }
    //更新persons表隐匿丑闻校友$num:校友ID
    public function hide($num)
    {
        require_once Root_Path . "/Model/mysql.php";
        $db  = new MySQL("127.0.0.1", "root", "", "alumnus");
        $sql = "UPDATE `persons` SET `data_state`=0 WHERE id=" . $num;
        $db->query($sql);
        $db->dbClose();
    }
    //更新persons表撤销隐匿丑闻校友$num:校友ID
    public function noHide($num)
    {
        require_once Root_Path . "/Model/mysql.php";
        $db  = new MySQL("127.0.0.1", "root", "", "alumnus");
        $sql = "UPDATE `persons` SET `data_state`=1 WHERE id=" . $num;
        $db->query($sql);
        $db->dbClose();
    }
    //查persons依据id数组
    public function find_all_arrId($array)
    {
        require_once Root_Path . "/Model/mysql.php";
        $db     = new MySQL("127.0.0.1", "root", "", "alumnus");
        $minSQL = '';
        foreach ($array as $key => $value) {
            $minSQL .= $value . ",";
        }
        $minSQL = substr($minSQL, 0, strlen($minSQL) - 1);
        $sql = "SELECT * FROM `persons` WHERE id IN (" . $minSQL . ") AND data_state = 1";
        $result = $db->query($sql);
        return $db->allAssoc($result);
        $db->dbClose();
    }
}
