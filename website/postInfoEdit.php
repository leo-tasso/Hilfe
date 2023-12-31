<?php
require_once 'bootstrap.php';

$templateParams["title"] = isLogged()?"Hilfe-Modifica Post":"Hilfe-Crea Post";
$templateParams["css"][] = "styleCreaPost.css";
$templateParams["css"][] = "styleCreaPost2.css";
$templateParams["js"][] = "../js/sidebar.js";
$templateParams["js"][] = "https://code.jquery.com/jquery-3.6.4.min.js";
$templateParams["js"][] = "../js/postInfoButtons.js";
$templateParams["page"] = "postInfo-form.php";
require 'template/base.php';
?>