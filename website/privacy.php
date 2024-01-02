<?php
require_once 'bootstrap.php';

$templateParams["title"] = "Hilfe-Salvati";
$templateParams["css"][] = "styleCondizioni.css";
$templateParams["js"][] = "../js/sidebar.js";
$templateParams["js"][] = "https://code.jquery.com/jquery-3.6.4.min.js";
$templateParams["page"] = "privacy-list.php";

require 'template/base.php';
?>