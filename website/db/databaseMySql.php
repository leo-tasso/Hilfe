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
        $stmt = $this->db->prepare("SELECT 	
        * FROM PostInterventi LIMIT ?");
        $stmt->bind_param('i', $n);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    public function getUserFromId($id){
        $stmt = $this->db->prepare("SELECT * FROM User where idUser =".$id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    public function getProfilePicPathFromId($id){
        $stmt = $this->db->prepare("SELECT FotoProfilo FROM User where idUser =".$id." LIMIT 1");
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    public function getAuthorFromHelpPost($id){
        $stmt = $this->db->prepare("SELECT * FROM User, PostInterventi  where idUser = Autore_idUser AND idPostIntervento =".$id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    public function getMaterialFromHelpPost($id){
        $stmt = $this->db->prepare("SELECT * FROM Materiale  where idPostIntervento =".$id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}


$dbh = new DatabaseHelperMySql("localhost", "root", "", "HilfeDb", 3306);

?>