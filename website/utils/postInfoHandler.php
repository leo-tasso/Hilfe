<?php
require_once '../bootstrap.php';
if (isset($_POST['titolo'], $_POST['testo'], $_FILES["postImg"])) {
   $titolo = $_POST['titolo'];
   $testo = $_POST['testo'];
   $postImg = $_FILES["postImg"];
   $id = isset($_POST["id"]) ? $_POST["id"] : null;

   if (!islogged()) {
      header('Location: ../registration.php');
   } else if ($id != null && islogged() && $id == $_SESSION["idUser"]) {
      if ($dbh->updatePostInfo($id, $titolo, $testo, $postImg["name"])) {
         $result = uploadImage(UPLOAD_DIR_POSTINFO_PIC, $_FILES["postImg"]);
         if ($result[0] == 0) {
            header('Location: ../postInfoEdit.php?error='.$result[1]);
         } else header('Location: ../profile.php');
      }
   } else if ($dbh->createPostInfo($titolo, $testo, $postImg["name"])) {
      $result = uploadImage(UPLOAD_DIR_POSTINFO_PIC, $_FILES["postImg"]);
      if ($result[0] == 0) {
         header('Location: ../postInfoEdit.php?error='.$result[1]);
      } else header('Location: ../profile.php');
   } else {
      header('Location: ../profile.php');
   }
}
