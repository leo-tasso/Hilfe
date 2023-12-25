<?php
require_once 'bootstrap.php';

$templateParams["title"] = "Hilfe-Home";
$templateParams["css"][0] = "styleHome.css";
$templateParams["js"][0] = "../js/sidebar.js";
$templateParams["js"][1] = "https://code.jquery.com/jquery-3.6.4.min.js";
$templateParams["js"][2] = "../js/buttonHandler.js";
$templateParams["page"] = "home.php";
$templateParams["helpPosts"] = $dbh->getHelpPosts($START_POST_NUMBER);

require 'template/base.php';
?>