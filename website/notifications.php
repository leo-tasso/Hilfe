<?php
require_once 'bootstrap.php';

$templateParams["title"] = "Hilfe-Notifiche";
$templateParams["css"][] = "styleNotifiche.css";
$templateParams["css"][] = "styleLayoutDesktop.css";
$templateParams["css"][] = "styleArticle.css";
$templateParams["js"][] = "../js/sidebar.js";
$templateParams["js"][] = "https://code.jquery.com/jquery-3.6.4.min.js";
$templateParams["page"] = "notifications-list.php";
require 'template/base.php';
?>