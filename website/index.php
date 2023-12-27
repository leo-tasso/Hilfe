<?php
require_once 'bootstrap.php';

$templateParams["title"] = "Hilfe-Home";
$templateParams["css"][0] = "styleHome.css";
$templateParams["css"][1] = "styleLayoutDesktop.css";
$templateParams["css"][2] = "styleArticle.css";
$templateParams["js"][0] = "../js/sidebar.js";
$templateParams["js"][1] = "https://code.jquery.com/jquery-3.6.4.min.js";
$templateParams["js"][2] = "../js/buttonHandler.js";
$templateParams["page"] = "home.php";
$templateParams["lastLoaded"] = 0;
$templateParams["lat"] = 0;
$templateParams["long"] = 0;
$templateParams["range"] = 100;
require 'template/base.php';
?>