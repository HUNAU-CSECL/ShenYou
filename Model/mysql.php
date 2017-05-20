<?php
class MySQL
{
    private $host;
    private $root;
    private $password;
    private $database;

    public function __construct($host, $root, $password, $database)
    {

        $this->host     = $host;
        $this->root     = $root;
        $this->password = $password;
        $this->database = $database;
        $this->connect();
    }

    public function connect()
    {
        $this->conn = mysqli_connect($this->host, $this->root, $this->password, $this->database) or die("DB Connnection Error !" . mysql_error());
        mysqli_query($this->conn, "set names utf8");
    }

    public function dbClose()
    {
        mysqli_close($this->conn);
    }

    public function query($sql)
    {
        return mysqli_query($this->conn, $sql);
    }

    public function assoc($result)
    {
        if ($result) {
            return mysqli_fetch_assoc($result);
        } else {
            return false;
        }
    }

    public function allAssoc($result)
    {
        if ($result) {
            return mysqli_fetch_all($result, MYSQLI_ASSOC);
        } else {
            return false;
        }

    }
}
