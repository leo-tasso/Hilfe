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
function uploadImage($path, $image){
    $imageName = basename($image["name"]);
    $fullPath = $path.$imageName;
    
    $maxKB = 5000;
    $acceptedExtensions = array("jpg", "jpeg", "png", "gif");
    $result = 0;
    $msg = "";
    //Controllo se immagine è veramente un'immagine
    $imageSize = getimagesize($image["tmp_name"]);
    if($imageSize === false) {
        //$msg .= "File caricato non è un'immagine! ";
    }
    //Controllo dimensione dell'immagine < 500KB
    if ($image["size"] > $maxKB * 1024) {
        $msg .= "File caricato pesa troppo! Dimensione massima è $maxKB KB. ";
    }

    //Controllo estensione del file
    $imageFileType = strtolower(pathinfo($fullPath,PATHINFO_EXTENSION));
    if(!in_array($imageFileType, $acceptedExtensions)){
        $msg .= "Accettate solo le seguenti estensioni: ".implode(",", $acceptedExtensions);
    }

    //Controllo se esiste file con stesso nome ed eventualmente lo rinomino
    if (file_exists($fullPath)) {
        $i = 1;
        do{
            $i++;
            $imageName = pathinfo(basename($image["name"]), PATHINFO_FILENAME)."_$i.".$imageFileType;
        }
        while(file_exists($path.$imageName));
        $fullPath = $path.$imageName;
    }

    //Se non ci sono errori, sposto il file dalla posizione temporanea alla cartella di destinazione
    if(strlen($msg)==0){
        if(!move_uploaded_file($image["tmp_name"], $fullPath)){
            $msg.= "Errore nel caricamento dell'immagine.". $image["error"];

        }
        else{
            $result = 1;
            $msg = $imageName;
        }
    }
    return array($result, $msg);
}
function generateRandomString($length) {
    $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $result = '';
    for ($i = 0; $i < $length; $i++) {
        $result .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $result;
}
?>