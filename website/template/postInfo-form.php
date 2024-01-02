<?php $post = null;
if (isset($_GET["id"])) {
    $post = $dbh->getInfoPost($_GET["id"]);
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
                        <input class="testo" type="textarea" id="annuncio" required name="testo" placeholder="Testo annuncio" <?php if (isset($_GET["id"])) {
                                                                                                                                    echo "value=\"" . $post["DescrizionePost"] . "\"";
                                                                                                                                } ?>>
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
                    <input class="annulla" type="reset" name="Annulla" value="Annulla" onclick='window.location.href = "../index.php"'>
                    <input class="pubblica" type="submit" name="Pubblica" value="Pubblica">
                </footer>
            </form>
            <?php if (isset($_GET["error"])) echo '<p class="error">'.$_GET["error"].'</p>' ?>
        </section>
    </main>
</div>