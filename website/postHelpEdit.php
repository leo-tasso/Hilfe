<?php
require_once 'bootstrap.php';

$templateParams["title"] = isLogged()?"Hilfe-Modifica Post":"Hilfe-Crea Post";
$templateParams["css"][] = "styleCreaPost.css";
$templateParams["js"][] = "../js/sidebar.js";
$templateParams["js"][] = "https://code.jquery.com/jquery-3.6.4.min.js";
$templateParams["js"][] = "../js/postHelpButtons.js";
$templateParams["page"] = "postHelp-form.php";
require 'template/base.php';
?>