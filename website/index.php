<?php
require_once 'bootstrap.php';

$templateParams["title"] = "Hilfe-Home";
$templateParams["css"][0] = "styleHome.css";
$templateParams["page"] = "home.php";
$templateParams["helpPosts"] = $dbh->getHelpPosts($START_POST_NUMBER);

require 'template/base.php';
?>