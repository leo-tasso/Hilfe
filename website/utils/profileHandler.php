<?php
require_once '../bootstrap.php';
if (isset($_POST['nome'], $_POST['cognome'], $_POST['data'], $_POST['email'], $_POST['password'])) {
   $nome = $_POST['nome'];
   $cognome = $_POST['cognome'];
   $data = $_POST['data'];
   $email = $_POST['email'];
   $password = $_POST['password'];
   $profilePic = isset($_FILES["profilePic"]) && $_FILES["profilePic"]["name"] != "" ? $_FILES["profilePic"]["name"] : null;
   $username = $_POST["username"];
   $addr = isset($_POST['addr']) && $_POST['addr'] != "" ? $_POST['addr'] : null;
   $phone = isset($_POST['phone']) && $_POST['phone'] != "" ? $_POST['phone'] : null;
   $bio = isset($_POST['bio']) && $_POST['bio'] != "" ? $_POST['bio'] : null;
   echo var_dump($_FILES["profilePic"]);
   if (!isLogged() && $dbh->checkRepetitions($username, $email)) {
      header('Location: ../registration.php?error="email o username già presenti"');
   } else if (!isLogged()) {
      if ($dbh->registerUser($nome, $cognome, $username, $data, $email, $password, $profilePic["name"], $phone, $addr, $bio)) {
         if ($profilePic != null) {
            uploadImage(UPLOAD_DIR_PROF_PIC, $_FILES["profilePic"]);
         }
         $dbh->login($email, $password, false);
         header('Location: ../profile.php');
      }
   } else if ($dbh->getUserFromId($_SESSION["idUser"])["Username"] == $username) {
      if ($dbh->updateUser($nome, $cognome, $username, $data, $email, $password, $profilePic["name"], $phone, $addr, $bio)) {
         if ($profilePic != null) {
            uploadImage(UPLOAD_DIR_PROF_PIC, $_FILES["profilePic"]);
         }
         $dbh->login($email, $password, false);
         header('Location: ../profile.php');
      }
   } else {
      header('Location: ../registration.php');
   }
} else {
   header('Location: ../registration.php');
}
