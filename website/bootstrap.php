<?php
ini_set('upload_max_filesize', '5M');
ini_set('post_max_size', '5M');
session_start();
define("UPLOAD_DIR", "./res/");
define("UPLOAD_DIR_PROF_PIC", "../res/profilePictures/");
define("UPLOAD_DIR_POSTINFO_PIC", "../res/postInfoPics/");
require_once("config.php");
require_once("secrets.php");
require_once("db/databaseMySql.php");
require_once("utils/functions.php");
$dbh->checkToken();
?>