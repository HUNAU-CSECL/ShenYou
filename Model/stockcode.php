<?php
class stockcode
{
    //查询新三板股票
    public function sb()
    {
        require_once Root_Path . "/Model/mysql.php";
        $db     = new MySQL("127.0.0.1", "root", "", "alumnus");
        $sql    = "SELECT * FROM `stockcode` WHERE id<13230 AND id>3252";
        $result = $db->query($sql);
        return $db->allAssoc($result);
        $db->dbClose();
    }
    //查询沪深股
    public function hs()
    {
//3253
        require_once Root_Path . "/Model/mysql.php";
        $db     = new MySQL("127.0.0.1", "root", "", "alumnus");
        $sql    = "SELECT * FROM `stockcode` WHERE id<3253";
        $result = $db->query($sql);
        return $db->allAssoc($result);
        $db->dbClose();
    }
    //查询中概股
    public function usa()
    {
        require_once Root_Path . "/Model/mysql.php";
        $db     = new MySQL("127.0.0.1", "root", "", "alumnus");
        $sql    = "SELECT * FROM `stockcode` WHERE id>13229";
        $result = $db->query($sql);
        return $db->allAssoc($result);
        $db->dbClose();
    }
    //查stockcode依据code
    public function find_by_code($string)
    {
        require_once Root_Path . "/Model/mysql.php";
        $db     = new MySQL("127.0.0.1", "root", "", "alumnus");
        $sql    = "SELECT * FROM `stockcode` WHERE code='" . $string . "'";
        $result = $db->query($sql);
        return $db->assoc($result);
        $db->dbClose();
    }
    //更新企业总市值$string:$code $num:$market
    public function update($string, $num)
    {
        require_once Root_Path . "/Model/mysql.php";
        $db  = new MySQL("127.0.0.1", "root", "", "alumnus");
        $sql = "UPDATE `stockcode` SET `market`='" . $num . "' WHERE `code`='" . $string . "'";
        $db->query($sql);
        $db->dbClose();
    }
    //查同一板各企业市值$which:hs、sb、usa
    public function allMarket($which)
    {
        require_once Root_Path . "/Model/mysql.php";
        $db = new MySQL("127.0.0.1", "root", "", "alumnus");
        if ($which == 'hs') {
            $sql = "SELECT `market` FROM `stockcode` WHERE id<13230 AND id>3252 AND `market` is not null";
        } elseif ($which == 'sb') {
            $sql = "SELECT `market` FROM `stockcode` WHERE id<3253 AND `market` is not null";
        } else {
            $sql = "SELECT `market` FROM `stockcode` WHERE id>13229 AND `market` is not null";
        }
        $result = $db->query($sql);
        return $db->allAssoc($result);
        $db->dbClose();
    }
    //更新market空白$which:hs、sb、usa,$num:同一板平均市值
    public function suppMarket($which, $num)
    {
        require_once Root_Path . "/Model/mysql.php";
        $db = new MySQL("127.0.0.1", "root", "", "alumnus");
        if ($which == 'hs') {
            $sql = "UPDATE `stockcode` SET `market`='" . $num . "' WHERE id<13230 AND id>3252 AND `market` is null";
        } elseif ($which == 'sb') {
            $sql = "UPDATE `stockcode` SET `market`='" . $num . "' WHERE id<3253 AND `market` is null";
        } else {
            $sql = "UPDATE `stockcode` SET `market`='" . $num . "' WHERE id>13229 AND `market` is null";
        }
        $db->query($sql);
        $db->dbClose();
    }
    //查stockcode依据id
    public function find_by_id($num)
    {
        require_once Root_Path . "/Model/mysql.php";
        $db     = new MySQL("127.0.0.1", "root", "", "alumnus");
        $sql    = "SELECT * FROM `stockcode` WHERE id = " . $num;
        $result = $db->query($sql);
        return $db->assoc($result);
        $db->dbClose();
    }
}
