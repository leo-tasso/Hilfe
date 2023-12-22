<?php
function isActive($pagename){
    if(basename($_SERVER['PHP_SELF'])==$pagename){
        echo " class='active' ";
    }
}
function addStyle($styleSheet){
    echo('<link href="'.$styleSheet.'" rel="stylesheet" type="text/css" />');
}
?>