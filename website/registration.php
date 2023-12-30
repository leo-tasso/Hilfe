<?php
require_once 'bootstrap.php';

$templateParams["title"] = "Hilfe-Registrazione";
$templateParams["css"][] = "styleRegistrazione.css";
$templateParams["js"][] = "../js/sidebar.js";
$templateParams["js"][] = "https://code.jquery.com/jquery-3.6.4.min.js";
$templateParams["js"][] = "../js/buttonHandler.js";
$templateParams["js"][] = "../js/registerScripts.js";
$templateParams["page"] = "registration-form.php";
require 'template/base.php';
?>