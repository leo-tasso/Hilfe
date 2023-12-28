<?php
require_once 'bootstrap.php';

$templateParams["title"] = "Hilfe-Seguaci";
$templateParams["css"][] = "styleLayoutDesktop.css";
$templateParams["css"][] = "styleFollowersSeguiti.css";
$templateParams["js"][] = "../js/sidebar.js";
$templateParams["js"][] = "../js/searcher.js";
$templateParams["js"][] = "https://code.jquery.com/jquery-3.6.4.min.js";
$templateParams["page"] = "follower-list.php";
require 'template/base.php';
?>