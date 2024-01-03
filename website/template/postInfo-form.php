<?php $post = null;
if (isLogged() && isset($_GET["id"]) && $dbh->getInfoPost($_GET["id"])["idUser"]==$_SESSION["idUser"]) {
    $post = $dbh->getInfoPost($_GET["id"]);
}
else if(isset($_GET["id"])){
    header('Location: ../index.php');
}
?>
<div class="centralContent">
    <main>
        <h1><?php if (!isset($_GET["id"])) {
                echo "Crea post";
            } else {
                echo "Modifica post";
            } ?></h1>
        <section>
            <form class="form" action="../utils/postInfoHandler.php" enctype="multipart/form-data" method="POST">
                <div class="container">
                    <div class="colonna1">
                        <?php if (isset($_GET["id"])) {
                            echo '<input type="hidden" id="additionalVar" name="id" value="' . $_GET["id"] . '"> ';
                        } ?>
                        <label for="titolo" hidden>Titolo</label><br />
                        <input type="text" id="titolo" name="titolo" placeholder="Titolo" <?php if (isset($_GET["id"])) {
                                                                                                echo "value=\"" . $post["TitoloPost"] . "\"";
                                                                                            } ?> />
                        <label for="annuncio" hidden>Testo annuncio</label><br />
                        <textarea class="testo" id="annuncio" required name="testo" placeholder="Testo annuncio" ><?php if (isset($_GET["id"])) {
                                                                                                                                    echo $post["DescrizionePost"];
                                                                                                                                } ?></textarea>
                    </div>
                    <div class="colonna2">
                        <h2>Carica immagine:</h2>
                        <div class="caricamento">
                            <img id="postPhoto" src="../res/postInfoPics/<?php if ($post != null) {
                                                                            echo $post["Foto"];
                                                                        } else {
                                                                            echo 'defaultPic.jpg';
                                                                        } ?>" alt="foto post" />
                            <input type="file" id="fileInput" style="display: none;" onchange="loadPhoto(this)" name="postImg" value="defaultPic.img">
                            <button type="button" class="carica" onclick="document.getElementById('fileInput').click()">Cambia foto</button>
                        </div>
                    </div>
                </div>
                <footer>
                <input class="cancella" type="button" name="Cancella" value="<?php if($post==null){echo 'Annulla';}else {echo 'Cancella Post';}?> " onclick="<?php if($post==null){echo 'toHomePage()';}else {echo 'deleteInfoPost('.$_GET["id"].')';}?>">
                    <input class="pubblica" type="submit" name="Pubblica" value="Pubblica">
                </footer>
            </form>
            <?php if (isset($_GET["error"])) echo '<p class="error">'.$_GET["error"].'</p>' ?>
        </section>
    </main>
</div>