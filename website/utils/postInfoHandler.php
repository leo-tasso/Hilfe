<?php
require_once '../bootstrap.php';
if (isset($_POST['titolo'], $_POST['testo'])) {
   $titolo = $_POST['titolo'];
   $testo = $_POST['testo'];
   $postImg = isset($_FILES["postImg"])?$_FILES["postImg"]["name"] : null;
   $id = isset($_POST["id"]) ? $_POST["id"] : null;

   if (!islogged()) {
      header('Location: ../profileEdit.php');
   } else if ($id != null && islogged() && $dbh->getInfoPost($id)["idUser"] == $_SESSION["idUser"]) {
      $oldPic = false;
      if ($postImg === null || $postImg =="") {
         $oldPic = true;
         $postImg = $dbh->getInfoPost($id)["Foto"];
      }
      if ($postImg != null && !$oldPic) {
         $result = uploadImage(UPLOAD_DIR_POSTINFO_PIC, $_FILES["postImg"]);
         if ($result[0] == 0) {
            header('Location: ../postInfoEdit.php?error=' . $result[1]);
            $postImg = null;
         } 
      }
      if ($dbh->updatePostInfo($id, $titolo, $testo, $postImg)) {
         header('Location: ../profile.php');
      }
   } else {
      $result = uploadImage(UPLOAD_DIR_POSTINFO_PIC, $_FILES["postImg"]);
      if ($result[0] == 0) {
         header('Location: ../postInfoEdit.php?error=' . $result[1]);
      } else if ($dbh->createPostInfo($titolo, $testo, $postImg["name"])) {
         header('Location: ../profile.php');
      } else {
         header('Location: ../postInfoEdit.php?error="Caricamento fallito"');
      }
   }
}
echo var_dump($_POST['titolo'], $_POST['testo'], $_FILES["postImg"]);
