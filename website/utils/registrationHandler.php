<?php
require_once '../bootstrap.php';
if (isset($_POST['nome'], $_POST['cognome'], $_POST['data'], $_POST['email'], $_POST['password'], $_FILES["profilePic"])) {
   $nome = $_POST['nome'];
   $cognome = $_POST['cognome'];
   $data = $_POST['data'];
   $email = $_POST['email'];
   $password = $_POST['password'];
   $profilePic = $_FILES["profilePic"];
   $username = $_POST["username"];
   if ($dbh->checkRepetitions($username, $email)){
      header('Location: ../registration.php?error="email o username già presenti"');
   }
   if ($dbh->registerUser($nome, $cognome, $username, $data, $email, $password, $profilePic["name"])) {
      uploadImage(UPLOAD_DIR_PROF_PIC, $_FILES["profilePic"]);
      $dbh->login($email, $password, false);
      header('Location: ../profile.php');
   } else {
      header('Location: ../registration.php');
   }
} else {
   header('Location: ../registration.php');
}
