<?php
session_start();
//$_SESSION["idUser"] = 3; /*TODO REMOVE, FOR DEBUG ONLY*/
define("UPLOAD_DIR", "./res/");
define("UPLOAD_DIR_PROF_PIC", "../res/profilePictures/");
require_once("config.php");
require_once("secrets.php");
require_once("db/databaseMySql.php");
require_once("utils/functions.php");
$dbh->checkToken();
?>