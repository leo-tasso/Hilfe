<?php

function isActive($pagename){
    if(basename($_SERVER['PHP_SELF'])==$pagename){
        echo " class='active' ";
    }
}
function addStyle($styleSheet){
    echo('<link href=../css/'.$styleSheet.' rel="stylesheet" type="text/css" />');
}
function nameSurnameFromuserId($id){
    require_once 'bootstrap.php';
    $user = $dbh->getUserFromId($id);
    return $user["Name"]." ".$user["Surname"];
}
?>