<?php
require_once 'bootstrap.php';

$templateParams["title"] = "Hilfe-Salvati";
$templateParams["css"][] = "styleHome.css";
$templateParams["css"][] = "styleLayoutDesktop.css";
$templateParams["css"][] = "styleArticle.css";
$templateParams["css"][] = "styleAnnunciSalvati.css";
$templateParams["js"][] = "../js/sidebar.js";
$templateParams["js"][] = "../js/buttonHandler.js";
$templateParams["js"][] = "https://code.jquery.com/jquery-3.6.4.min.js";
$templateParams["page"] = "saved-list.php";
$templateParams["lastLoadedHelp"] = 0;
$templateParams["lastLoadedInfo"] = 0;
$templateParams["lastLoaded"] = 0;


require 'template/base.php';
?>