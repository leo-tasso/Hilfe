<?php
require_once 'bootstrap.php';

$templateParams["title"] = "Hilfe-Login";
$templateParams["css"][0] = "styleLogin.css";
$templateParams["css"][1] = "styleLayoutDesktop.css";
$templateParams["js"][0] = "../js/sidebar.js";
$templateParams["js"][1] = "https://code.jquery.com/jquery-3.6.4.min.js";
$templateParams["page"] = "login-form.php";
require 'template/base.php';
?>