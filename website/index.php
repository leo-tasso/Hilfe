<?php
require_once("bootstrap.php");

$templateparams["title"] = "Hilfe - Home";
$templateparams["name"] = "explore.php";
$templateparams["articles"] = $dbh->getHelpPosts(2);

require("template/explore.php");

?>