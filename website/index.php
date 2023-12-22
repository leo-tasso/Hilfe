<?php
require_once 'bootstrap.php';

$templateParams["title"] = "Hilfe-Home";
$templateParams["css"] = "styleHome.php";
$templateParams["page"] = "Home.php";
$templateParams["posts"] = $dbh->getHelpPosts($START_POST_NUMBER);

require 'template/base.php';
?>