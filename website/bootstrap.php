<?php
session_start();
$_SESSION["idUser"] = 3;
define("UPLOAD_DIR", "./res/");
require_once("config.php");
require_once("db/databaseMySql.php");
require_once("utils/functions.php");
?>