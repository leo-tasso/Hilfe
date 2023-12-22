<?php
require_once 'bootstrap.php';

$templateParams["title"] = "Hilfe-Home";
$templateParams["css"][0] = "styleHome.php";
$templateParams["page"] = "home.php";
$templateParams["posts"] = $dbh->getHelpPosts($START_POST_NUMBER);

require 'template/base.php';
?>