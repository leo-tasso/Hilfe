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
   if (!isLogged() && $dbh->checkRepetitions($username, $email)) {
      header('Location: ../profileEdit.php?error="email o username già presenti"');
   } else if (!isLogged()) {
      if ($dbh->registerUser($nome, $cognome, $username, $data, $email, $password, $profilePic, $phone, $addr, $bio)) {
         $dbh->login($email, $password, false);
         if ($profilePic != null) {
            $result = uploadImage(UPLOAD_DIR_PROF_PIC, $_FILES["profilePic"]);
            if ($result[0] == 0) {
               header('Location: ../profileEdit.php?error='.$result[1]);
            } else header('Location: ../profile.php');
         } else
            header('Location: ../profile.php');
      }
   } else if ($dbh->getUserFromId($_SESSION["idUser"])["Username"] == $username) {
      $oldPic = false;
      if($profilePic === null){
         $oldPic = true;
         $profilePic = $dbh->getUserFromId($_SESSION["idUser"])["FotoProfilo"];
      }
      if ($dbh->updateUser($nome, $cognome, $username, $data, $email, $password, $profilePic, $phone, $addr, $bio)) {
         $dbh->login($email, $password, false);
         if ($profilePic != null && !$oldPic) {
            $result = uploadImage(UPLOAD_DIR_PROF_PIC, $_FILES["profilePic"]);
            if ($result[0] == 0) {
               header('Location: ../profileEdit.php?error='.$result[1]);
            } else header('Location: ../profile.php');
         } else
            header('Location: ../profile.php');
      }
   } else {
      header('Location: ../profileEdit.php?error="You are not the owner of the profile"');
   }
} else {
   header('Location: ../profileEdit.php?error="parameters unset"');
}
