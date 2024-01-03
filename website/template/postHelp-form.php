<?php $post = null;
if (isset($_GET["id"])) {
    $post = $dbh->getHelpPost($_GET["id"]);
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
            <form class="form" action="../utils/postHelpHandler.php" enctype="multipart/form-data" method="POST">
                <div class="container">
                    <div class="colonna1">
                    <?php if (isset($_GET["id"])) {echo '<input type="hidden" id="additionalVar" name="id" value="'.$_GET["id"].'"> ';}?>

                        <label for="titolo" hidden>Titolo</label><br />
                        <input type="text" id="titolo" class="titolo" name="titolo"  required placeholder="Titolo" <?php if (isset($_GET["id"])) {
                                                                                                                echo "value=\"" . $post["TitoloPost"] . "\"";
                                                                                                            } ?> />
                        <label for="annuncio" hidden>Testo annuncio</label><br />
                        <input class="testo" type="textarea" id="annuncio" required name="testo" placeholder="Testo annuncio" <?php if (isset($_GET["id"])) {
                                                                                                                echo "value=\"" . $post["DescrizionePost"] . "\"";
                                                                                                            } ?>>
                        <div class="luogo">
                            <label for="indirizzo">Indirizzo:</label>
                            <input type="text" id="indirizzo" required  name="indirizzo" <?php if (isset($_GET["id"])) {
                                                                                    echo "value=\"" . $post["Indirizzo"] . "\"";
                                                                                } ?> />
                        </div>
                    </div>
                    <div class="colonna2">
                        <div class="giorno">
                            <label for="date">Data:</label>
                            <input id="date" type="date"  required name="giorno" <?php if (isset($_GET["id"])) {
                                                                            echo "value=\"" . explode(' ', $post["DataIntervento"])[0] . "\"";
                                                                        } ?> />
                        </div>
                        <div class="ora">
                            <label for="time">Ora:</label>
                            <input id="time" type="time"  required name="ora" <?php if (isset($_GET["id"])) {
                                                                        echo "value=\"" . explode(' ', $post["DataIntervento"])[1] . "\"";
                                                                    } ?> />
                        </div>
                        <div class="numPersone">
                            <p class="pers">Persone:</p>
                            <div class="persone">
                                <button class="meno" type="button" onclick="decrement()">-</button>
                                <label for="numero" hidden>Numero</label>
                                <input type="text" name="personeRichieste" required class="numero" id="numero" value=<?php if (isset($_GET["id"])) {
                                                                            echo "\"" . $post["PersoneRichieste"] . "\"";
                                                                        } else {
                                                                            echo "\"5\"";
                                                                        } ?> readonly>
                                <button class="piu" type="button" onclick="increment()">+</button>
                            </div>
                        </div>
                        <h2>Materiale richiesto</h2>
                        <?php if (isset($_GET["id"])) {
                            $counter = 0;
                            foreach ($dbh->getMaterialFromHelpPost($_GET["id"]) as $material) : ?>
                                <div class="materiale">
                                    <label for="oggetto<?php echo $counter ?>" hidden>Oggetto</label>
                                    <input type="text" class="oggetto"  required id="oggetto<?php echo $counter ?>" name="oggetto[<?php echo $counter ?>]" placeholder="Oggetto" <?php echo "value=\"" . $material["DescrizioneMateriale"] . "\""; ?> />
                                    <label for="quantita<?php echo $counter ?>" hidden>Quantità</label>
                                    <input type="number" class="quantita"  required id="quantita<?php echo $counter ?>" name="quantita[<?php echo $counter ?>]" min="0" max="99" <?php echo "value=\"" . $material["Unita"] . "\""; ?> onchange="checkVariation(this)" />
                                </div>
                                <?php $counter++; ?>
                        <?php endforeach;
                        } else {
                            echo '
                        <div class="materiale">
                            <label for="oggetto" hidden>Oggetto</label>
                            <input type="text" class="oggetto" id="oggetto" required name="oggetto[0]" placeholder="Oggetto" />
                            <label for="quantita" hidden>Quantità</label>
                            <input type="number"  class="quantita" id="quantita" required name="quantita[0]" min="0" max="99" value="1" onchange="checkVariation(this)"/>
                        </div>';
                        } ?>
                        <input class="aggiungi" type="button" name="Aggiungi+" value="Aggiungi+" onclick="addMaterial()">
                    </div>
                </div>
                <footer>
                    <input class="cancella" type="button" name="Cancella" value="Cancella post" onclick="<?php if($post==null){echo 'window.location.href = "../index.php"';}else {echo 'deleteHelpPost('.$_GET["id"].')';}?>">
                    <input class="pubblica" type="submit" name="Pubblica" value="Pubblica" >
                </footer>
            </form>
        </section>
    </main>
</div>