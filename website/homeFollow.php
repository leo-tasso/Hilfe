<?php
require_once 'bootstrap.php';

$templateParams["title"] = "Hilfe-Home";
$templateParams["css"][] = "styleHome.css";
$templateParams["css"][] = "styleLayoutDesktop.css";
$templateParams["css"][] = "styleArticle.css";
$templateParams["js"][] = "../js/sidebar.js";
$templateParams["js"][] = "https://code.jquery.com/jquery-3.6.4.min.js";
$templateParams["js"][] = "../js/buttonHandler.js";
$templateParams["page"] = "homeFollow-list.php";
$templateParams["lastLoadedInfo"] = 0;
require 'template/base.php';
?>