<?php
/**
 * Created by PhpStorm.
 * User: aysonsteven
 * Date: 9/1/2017
 * Time: 10:57 PM
 */

class db{
    private $dbhost = 'localhost';
    private $dbuser = 'root';
    private $dbpass = '';
    private $dbname = 'progressivedb';

    public function connect(){
        $mysql_connect_str = "mysql:host=$this->dbhost;dbname=$this->dbname;";
        $dbConnection = new PDO($mysql_connect_str, $this->dbuser, $this->dbpass);
        $dbConnection->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
        return $dbConnection;
    }
}