<?php
require("databaseHelper.php");
class DatabaseHelperMySql implements DatabaseHelper{
    private $db;
    public function __construct($servername,$username,$password,$dbname){
        $this->db = new mysqli($servername,$username,$password,$dbname);
        if($this->db->connect_error){
            die("Connection failed: " . $this->db->connect_error);
        }
    }
    public function getHelpPosts($n){
        /*
        $stmt = $this->db->prepare("SELECT * FROM postinterventi LIMIT ?");
        $stmt->bind_param('i', $n);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
        */
    }
}


$dbh = new DatabaseHelperMySql("localhost", "root", "", "HilfeDb", 3306);

?>