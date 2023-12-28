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
function isLogged(){
    return isset($_SESSION['idUser']) && !empty($_SESSION['idUser']);
}
function getCoordinates($address) {
    $email = EMAIL;
    $address = urlencode($address);
    $url = "https://nominatim.openstreetmap.org/search?format=json&limit=1&q={$address}&email={$email}";

    $response = file_get_contents($url);

    if ($response !== false) {
        $data = json_decode($response, true);

        if (!empty($data) && isset($data[0])) {
            $latitude = $data[0]['lat'];
            $longitude = $data[0]['lon'];

            return ['latitude' => $latitude, 'longitude' => $longitude];
        } else {
            return null; 
        }
    } else {
        return null;
    }
}
function generateUniqueToken() {
    return bin2hex(random_bytes(32)); // Generates a 64-character hex token
}
?>