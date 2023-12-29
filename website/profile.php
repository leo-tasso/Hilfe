<?php
require_once 'bootstrap.php';

$templateParams["title"] = "Hilfe-Profilo";
$templateParams["css"][] = "styleUserProfile.css";
$templateParams["css"][] = "styleArticle.css";
$templateParams["css"][] = "styleLayoutDesktop.css";
$templateParams["js"][] = "../js/sidebar.js";
$templateParams["js"][] = "https://code.jquery.com/jquery-3.6.4.min.js";
$templateParams["js"][] = "../js/buttonHandler.js";
$templateParams["page"] = "profile-list.php";
$templateParams["lastLoaded"] = 0;
require 'template/base.php';
?>