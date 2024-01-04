<?php
require_once '../bootstrap.php';
if (isset($_POST['titolo'], $_POST['testo'], $_POST['indirizzo'], $_POST['giorno'], $_POST['ora'], $_POST['personeRichieste'])) {
   $titolo = $_POST['titolo'];
   $testo = $_POST['testo'];
   $indirizzo = $_POST['indirizzo'];
   $giorno = $_POST['giorno'];
   $ora = $_POST['ora'];
   $personeRichieste = $_POST['personeRichieste'];
   $oggetto = isset($_POST['oggetto']) ? $_POST['oggetto'] : null;
   $quantita = isset($_POST['quantita']) ? $_POST['quantita'] : null;
   $id = isset($_POST["id"]) ? $_POST["id"] : null;

   if (!islogged()) {
      header('Location: ../profileEdit.php');
   } else if ($id != null && islogged() && $dbh->getHelpPost($id)["Autore_idUser"] == $_SESSION["idUser"]) {
      if ($dbh->updatePostHelp($_POST["id"], $_POST['titolo'], $_POST['testo'], $_POST['indirizzo'], $_POST['giorno'], $_POST['ora'], $_POST['personeRichieste'], $oggetto, $quantita)) {
         header('Location: ../profile.php');
      }
   } else if ($dbh->newPostHelp($_POST['titolo'], $_POST['testo'], $_POST['indirizzo'], $_POST['giorno'], $_POST['ora'], $_POST['personeRichieste'], $oggetto, $quantita)) {
      header('Location: ../profile.php');
   } else {
      header('Location: ../profile.php');
   }
}
