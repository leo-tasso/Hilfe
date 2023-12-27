<article id="<?php echo $post["idPostIntervento"]; ?>, <?php echo $post["PersoneRichieste"]; ?>">
    <header>
        <h1><?php echo $post["TitoloPost"]; ?></h1>
        <div class="infoUtente">
            <a href="profiloUtente.html" class="nomeAutore"><?php $autore = $dbh->getAuthorFromHelpPost($post["idPostIntervento"])[0];
                                                            echo $autore["Name"] . " " . $autore["Surname"] ?></a>
            <a href="profiloUtente.html"><img class="profilo" id="profilo" src="../<?php echo $dbh->getProfilePic($post["Autore_idUser"]); ?>" alt="profilo"></a>
        </div>
    </header>
    <div class="content">
        <section lang="zxx">
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
        <?php insertmap($post["idPostIntervento"], $post["PosizioneLongitudine"], $post["PosizioneLatitudine"], $post["Indirizzo"]); ?>
        <footer>
            <button type="button" class="buttonSalva" id="buttonSalva<?php echo $post["idPostIntervento"] ?>" onclick="toggleSalva(<?php echo $post["idPostIntervento"] ?>,<?php echo $post["PersoneRichieste"]; ?>)"><img class="iconButton" src="../Icons/HeartEmpty.svg" alt="">Salva</button><button type="button" class="buttonPartecipa" id="buttonPartecipa<?php echo $post["idPostIntervento"] ?>" name="Partecipa" onclick="togglePartecipa(<?php echo $post["idPostIntervento"] ?>,<?php echo $post["PersoneRichieste"]; ?>)">Partecipa</button><button type="button" class="buttonPartecipanti" onclick="openPopup(<?php echo $post["idPostIntervento"] ?>)" data-progress-text="Partecipa" data-complete-text="Al completo"><span id="progress<?php echo $post["idPostIntervento"] ?>" class="button__progress"></span><span id="partecipaLablel<?php echo $post["idPostIntervento"] ?>" class="button__text">Partecipanti 0/0</span></button>
            <div id="popup">
                <h3>Utenti partecipanti</h3>
                <button class="closePopup" onclick="closePopup()">Indietro</button>
            </div>
            <div id="overlay"></div>
        </footer>
</article>
<?php $templateParams["lastLoaded"] = $post["idPostIntervento"]; ?>