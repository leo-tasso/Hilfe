<?php require_once('map.php'); ?>
<?php if(isset($post["idPostIntervento"])):?>
<article class="HelpPost" id="<?php echo $post["idPostIntervento"]; ?>,<?php echo $post["PersoneRichieste"]; ?>">
    <header>
        <h1><?php echo $post["TitoloPost"]; ?></h1>
        <div class="infoUtente">
            <a href="profile.php?id=<?php echo $post["Autore_idUser"];?>" class="nomeAutore"><?php $autore = $dbh->getAuthorFromHelpPost($post["idPostIntervento"]);
                                                            echo $autore["Name"] . " " . $autore["Surname"] ?></a>
            <a href="profile.php?id=<?php echo $post["Autore_idUser"];?>"><img class="profilo" id="profilo<?php echo $post["Autore_idUser"];?>" src="../<?php echo $dbh->getProfilePic($post["Autore_idUser"]); ?>" alt="profilo"></a>
            <?php if(isLogged() && $post["Autore_idUser"]==$_SESSION["idUser"]){echo '<a href="postHelp-form.php?id=<?php echo $post["idPostIntervento"];?>"><img class="penna" src="../Icons/Pen.svg" alt="modifica Post"></a>';}?>
        </div>
    </header>
    <div class="content">
        <section lang="ita">
            <h3>Dettagli Annuncio</h3>
            <p class="date"><?php echo $post["DataPubblicazione"]; ?></p>
            <p><?php echo $post["DescrizionePost"]; ?></p>
            <p class="time">Data intervento: <?php echo $post["DataIntervento"]; ?></p>
            <?php if ($autore["PhoneNumber"] != null) : ?>
                <p class="phoneNumber">Numero di telefono: <?php echo $autore["PhoneNumber"]; ?></p>
            <?php endif ?>
            <p class="people">Persone necessarie: <?php echo $post["PersoneRichieste"]; ?></p>
            <?php $materiale = $dbh->getMaterialFromHelpPost($post["idPostIntervento"]);
            if (count($materiale) > 0) : ?>
                <table>
                    <tr>
                        <th>Materiale necessario</th>
                        <th>Quantità</th>
                    </tr>
                    <?php foreach ($materiale as $item) : ?>
                        <tr>
                            <td><?php echo $item["DescrizioneMateriale"]; ?></td>
                            <td><?php echo $item["Unita"]; ?></td>
                        </tr>
                    <?php endforeach ?>
                </table>
            <?php endif ?>
        </section>
        <?php insertmap($post["idPostIntervento"]) ?>
    </div>
    <footer>
        <button type="button" class="buttonSalva" id="buttonSalva<?php echo $post["idPostIntervento"] ?>" onclick=<?php if(isLogged()) {echo "\"toggleSalva(".$post["idPostIntervento"]." ,".$post["PersoneRichieste"].")\"";}else{echo "toLoginPage()";}?>><img class="iconButton" src="../Icons/HeartEmpty.svg" alt="">Salva</button><button type="button" class="buttonPartecipa" id="buttonPartecipa<?php echo $post["idPostIntervento"] ?>" name="Partecipa" onclick=<?php if(isLogged()) {echo "\"togglePartecipa(".$post["idPostIntervento"]." ,".$post["PersoneRichieste"].")\"";}else{echo "toLoginPage()";}?>>Partecipa</button><button type="button" class="buttonPartecipanti" onclick=<?php echo "\"openPopup(".$post["idPostIntervento"].")\""?> data-progress-text="Partecipa" data-complete-text="Al completo"><span id="progress<?php echo $post["idPostIntervento"] ?>" class="button__progress"></span><span id="partecipaLablel<?php echo $post["idPostIntervento"] ?>" class="button__text">Partecipanti 0/0</span></button>
    </footer>
</article>
<?php require_once('popupOverlay.php');?>
<?php $templateParams["lastLoaded"] = $post["idPostIntervento"]; ?>
<?php $templateParams["lastLoadedHelp"] = $post["idPostIntervento"]; ?>

<?php else:?>
    <article class="tipo2 InfoPost" id="<?php echo "comunicazione".$post["idPostComunicazione"]; ?>">
    <header>
        <h1><?php echo $post["TitoloPost"]; ?></h1>
        <div class="infoUtente">
            <a href="profile.php?id=<?php echo $post["idUser"];?>" class="nomeAutore"><?php $autore = $dbh->getAuthorFromInfoPost($post["idPostComunicazione"]);
                                                            echo $autore["Name"] . " " . $autore["Surname"] ?></a>
            <a href="profile.php?id=<?php echo $post["idUser"];?>"><img class="profilo" id="profilo<?php echo $post["idUser"];?>" src="../<?php echo $dbh->getProfilePic($post["idUser"]); ?>" alt="profilo"></a>
            <?php if(isLogged() && $post["idUser"]==$_SESSION["idUser"]){echo '<a href="postInfo-form.php?id=<?php echo $post["idPostComunicazione"];?>"><img class="penna" src="../Icons/Pen.svg" alt="modifica Post"></a>';}?>
        </div>
    </header>
            <section class="content">
                <h3>Dettagli Annuncio</h3>
                <div class="text">
                <p class="date"><?php echo $post["DataPubblicazione"]; ?></p>
                <p><?php echo $post["DescrizionePost"]; ?></p>
                </div>
                <img class="fotoPost" src="<?php echo UPLOAD_DIR_POSTINFO_PIC.$post["Foto"];?>" alt="foto post"/>
            </section>
            <footer>
            <button type="button" class="miPiace" id="buttonMiPiace<?php echo $post["idPostComunicazione"] ?>" onclick=<?php if(isLogged()) {echo "\"toggleLike(".$post["idPostComunicazione"].")\"";}else{echo "toLoginPage()";}?>><img class="iconButton" src="../Icons/HeartEmpty.svg" alt="">Mi piace</button>
                <section class="sectionCommenti">
                <h3 class="sezioneCommenti">Commenti</h3>
                <form class="formCommenti" action="#" method="POST">
                    <label for="commenta<?php echo $post["idPostComunicazione"] ?>" hidden>Commenta</label>
                    <input type="text" class="commentaField" id="commenta<?php echo $post["idPostComunicazione"] ?>" placeholder="Commenta" name="commenta" onkeydown="publishOnEnter(event, <?php echo $post["idPostComunicazione"] ?>)">
                    <button class="pubblicaCommento" type="button" id="publishButton<?php echo $post["idPostComunicazione"] ?>" value="pubblica" onclick="publish(<?php echo $post["idPostComunicazione"] ?>)"><img class="iconButton" src="../Icons/SendArrow.svg" alt=""></button>
                </form>
                <ul class="commenti">
                </ul>
            </section>
            </footer>
        </article>
        <?php $templateParams["lastLoaded"] = $post["idPostComunicazione"]; ?>
        <?php $templateParams["lastLoadedInfo"] = $post["idPostComunicazione"]; ?>
    <?php endif;?>