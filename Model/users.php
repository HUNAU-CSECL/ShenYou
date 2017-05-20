<?php
class users
{
    //rWOVjJGH4AkyVvbV
    //查users表全部用户
    public function allUsers()
    {
        require_once Root_Path . "/Model/mysql.php";
        $db     = new MySQL("127.0.0.1", "root", "", "alumnus");
        $sql    = "SELECT * FROM `users` ORDER BY type DESC";
        $result = $db->query($sql);
        return $db->allAssoc($result);
        $db->dbClose();
    }
    //更新users表更新普通用户为管理员$num:用户id
    public function toAdmin($num)
    {
        require_once Root_Path . "/Model/mysql.php";
        $db  = new MySQL("127.0.0.1", "root", "", "alumnus");
        $sql = "UPDATE `users` SET `type`=1 WHERE `id`=$num";
        $db->query($sql);
        $db->dbClose();
    }
    //查users表登录验证$string1:邮箱号 $string2:密码
    public function login($string1, $string2)
    {
        require_once Root_Path . "/Model/mysql.php";
        $db     = new MySQL("127.0.0.1", "root", "", "alumnus");
        $sql    = "SELECT * FROM `users` WHERE mail='" . $string1 . "' AND password='" . $string2 . "' AND data_state=1";
        $result = $db->query($sql);
        return $db->allAssoc($result);
        $db->dbClose();
    }
    //查users表依据邮箱号$string:邮箱号
    public function find_by_mail($string)
    {
        require_once Root_Path . "/Model/mysql.php";
        $db     = new MySQL("127.0.0.1", "root", "", "alumnus");
        $sql    = "SELECT * FROM `users` WHERE mail='" . $string . "'";
        $result = $db->query($sql);
        return $db->allAssoc($result);
        $db->dbClose();
    }
    //插users表新增用户
    public function insert($name, $mail, $password, $sch_id)
    {
        require_once Root_Path . "/Model/mysql.php";
        $db  = new MySQL("127.0.0.1", "root", "", "alumnus");
        $sql = "INSERT INTO `users`(`name`, `mail`,`password`,`sch_id`) VALUES ('" . $name . "','" . $mail . "','" . $password . "',$sch_id)";
        $db->query($sql);
        $db->dbClose();
    }
    //插入users表的id
    public function insertId()
    {
        require_once Root_Path . "/Model/mysql.php";
        $db     = new MySQL("127.0.0.1", "root", "", "alumnus");
        $sql    = "SELECT max(id) from `users`";
        $result = $db->query($sql);
        $array  = $db->assoc($result);
        return $array['max(id)'];
        $db->dbClose();
    }
    //查user表依据id
    public function find_by_id($id)
    {
        require_once Root_Path . "/Model/mysql.php";
        $db     = new MySQL("127.0.0.1", "root", "", "alumnus");
        $sql    = "SELECT * FROM `users` WHERE id=$id";
        $result = $db->query($sql);
        return $db->assoc($result);
        $db->dbClose();
    }
    //更新users表更新用户密码
    public function modify_password($string1, $string2)
    {
        // $mail = stripslashes($string1);
        require_once Root_Path . "/Model/mysql.php";
        $db  = new MySQL("127.0.0.1", "root", "", "alumnus");
        $sql = "UPDATE `users` SET `password`='" . $string1 . "' WHERE `mail`='" . $string2 . "'";
        $db->query($sql);
        $db->dbClose();
    }
}
