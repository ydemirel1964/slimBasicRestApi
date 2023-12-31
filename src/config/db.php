<?php
class Db
{
    private $dbHost = "localhost";
    private $dbUser = "root";
    private $dbPassword = "";
    private $dbName = "course_coupons2";

    public function connect()
    {
        $mysql = "mysql:host=$this->dbHost;dbname=$this->dbName;charset=utf8";
        $connection = new PDO("$mysql", "$this->dbUser", "$this->dbPassword");
        $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $connection;
    }

}