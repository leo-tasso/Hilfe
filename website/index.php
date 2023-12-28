<?php
require_once 'bootstrap.php';

$templateParams["title"] = "Hilfe-Home";
$templateParams["css"][] = "styleHome.css";
$templateParams["css"][] = "styleLayoutDesktop.css";
$templateParams["css"][] = "styleArticle.css";
$templateParams["js"][] = "../js/sidebar.js";
$templateParams["js"][] = "https://code.jquery.com/jquery-3.6.4.min.js";
$templateParams["js"][] = "../js/buttonHandler.js";
$templateParams["js"][] = "../js/updateRangeBar.js";
$templateParams["page"] = "home.php";
$templateParams["lastLoaded"] = 0;
$templateParams["lat"] = 0;
$templateParams["long"] = 0;
$templateParams["range"] = 100;
require 'template/base.php';
?>