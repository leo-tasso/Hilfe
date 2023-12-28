<?php
require_once '../bootstrap.php';
if(isset($_POST['name'], $_POST['password'])) { 
   $email = $_POST['name'];
   $remember = $_POST['ricordami'];
   $password = $_POST['password']; // Recupero la password criptata.
   if($dbh->login($email, $password,$remember) == true) {
      // Login eseguito
      header('Location: ../profile.php');
   } else {
      // Login fallito
      header('Location: ../login.php?error=1');
   }
} else { 
   // Le variabili corrette non sono state inviate a questa pagina dal metodo POST.
   echo 'Invalid Request';
}
?>