<?php include 'map.php'; ?>
<header class="newPostContainer">
    <div class="navbar">
        <a class="default" href="#"><img class="icon" src="../Icons/Lens.svg" alt="">Esplora</a>
        <a href="#"><img class="icon" src="../Icons/Profile.svg" alt="">Followers</a>
    </div>
    <div class="newPost">
        <img src="../<?php echo $dbh->getSelfProfilePic();?>" alt="Profilo 1" class="profilo">
        <a href="creaPost.html">Hai bisogno di aiuto?🛟 Nuovo post</a>
    </div>
</header>
<div class="centralContent">
    <aside class="leftAside"></aside>
    <main class="articles">

<?php foreach($templateParams["helpPosts"] as $post): ?>
        <article>
            <header>
                <h2><?php echo $post["TitoloPost"]; ?></h2>
                <div class="infoUtente">
                    <a href="profiloUtente.html" class="nomeAutore"><?php $autore = $dbh->getAuthorFromHelpPost($post["idPostIntervento"])[0];
                    echo $autore["Name"]." ".$autore["Surname"] ?></a>
                    <a href="profiloUtente.html"><img class="profilo" id="profilo" src="../<?php echo $dbh->getProfilePic($post["Autore_idUser"]);?>" alt="profilo"></a>
                </div>
            </header>
            <div class="content">
                <section lang="zxx">
                    <h3>Dettagli Annuncio</h3>
                    <p class="date"><?php echo $post["DataPubblicazione"]; ?></p>
                    <p><?php echo $post["DescrizionePost"]; ?></p>
                    <p class="time"><?php echo $post["DataIntervento"]; ?></p>
                    <?php if($autore["PhoneNumber"]!=null):?>
                    <p class="phoneNumber">Numero di telefono: <?php echo $autore["PhoneNumber"]; ?></p>
                    <?php endif ?>
                    <p class="people">Persone necessarie: <?php echo $post["PersoneRichieste"]; ?></p>
                    <?php $materiale = $dbh->getMaterialFromHelpPost($post["idPostIntervento"]);
                    if (count($materiale)>0):?>
                    <table>
                        <tr>
                            <th>Materiale necessario</th>
                            <th>Quantità</th>
                        </tr>
                        <?php foreach($materiale as $item):?>
                        <tr>
                            <td><?php echo $item["DescrizioneMateriale"]; ?></td>
                            <td><?php echo $item["Unita"]; ?></td>
                        </tr>
                        <?php endforeach?>
                    </table>
                    <?php endif?>
                </section>
            <?php insertmap($post["idPostIntervento"],$post["PosizioneLongitudine"],$post["PosizioneLatitudine"],$post["Indirizzo"]); ?> 
            <footer>
                <button type="button" onclick="openPopup()" class="buttonSalva"><img class="iconButton" src="../Icons/Heart.svg" alt="">Salva</button><input type="button" class="buttonPartecipa" name="Partecipa" value="Partecipa"><button type="button" class="buttonPartecipanti" onclick="openPopup()" data-progress-text="Partecipa" data-complete-text="Al completo"><span class="button__progress"></span><span class="button__text">Partecipanti 2/10</span></button>
                <div id="popup">
                    <h3>Utenti partecipanti</h3>
                    <a href="profiloUtente.html" class="infoUser">
                        <img src="../fotoProfiloIniziale.jpg" alt="Profilo 1">
                        <div class="profile">
                            <span>Luna Fabbri</span>
                            <p class="amici">Ti Segue - n followers comuni</p>
                        </div>
                    </a>
                    <a href="profiloUtente.html" class="infoUser">
                        <img src="../fotoProfiloIniziale.jpg" alt="Profilo 1">
                        <div class="profile">
                            <span>Lupo Lucio</span>
                            <p class="amici"> Ti segue - n followers comuni</p>
                        </div>
                    </a>
                    <button class="closePopup" onclick="closePopup()">Indietro</button>
                </div>
                <div id="overlay"></div>
                <script>
                    function openPopup() {
                        document.getElementById("popup").style.display = "block";
                        document.getElementById("overlay").style.display = "block";
                    }

                    function closePopup() {
                        document.getElementById("popup").style.display = "none";
                        document.getElementById("overlay").style.display = "none";
                    }
                </script>
            </footer>
        </article>
        <?php endforeach; ?>

        <button type="button" class="buttonAltriPost">Altri Post</button>
    </main>
    <aside>
        <h3>Profili suggeriti</h3>
        <?php foreach($dbh->getSuggestedUsers(START_SUGGESTED_USERS) as $user):?>
        <a href="profiloUtente.php/<?php echo $user["idUser"] ?>" class="infoUser">
            <img src="../<?php echo $dbh->getProfilePic($user["idUser"]);?>" alt="immagine profilo di <?php echo $user["Name"] ?> <?php echo $user["Surname"] ?>">
            <div class="profile">
                <span><?php echo $user["Name"] ?> <?php echo $user["Surname"] ?></span>
                <p class="amici"><?php 
                if(isset($user["seguace"])){ echo "Ti Segue";} 
                if(isset($user["seguace"]) && isset($user["NumSeguitiInComune"])){ echo " - ";}
                if(isset($user["NumSeguitiInComune"])){ echo $user["NumSeguitiInComune"]." Seguiti in comune";}
                if(isset($user["Motivazione"])){ echo $user["Motivazione"];}
                ?></p>
            </div>
        </a>
        <?php endforeach;?>
    </aside>
</div>