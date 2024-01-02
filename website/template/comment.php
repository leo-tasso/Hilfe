<li class="commento">
    <div class="fotoECommento">
        <a href="profile.php?id=<?php echo $comment["Autore"]; ?>"><img class="profilo" src="../<?php echo $dbh->getProfilePic($comment["Autore"]); ?>" alt="foto profilo autore"></a>
        <div class="infoCommento">
            <a href="profile.php?id=<?php echo $comment["Autore"]; ?>" class="nomeAutore"><?php $autore = $dbh->getUserFromId($comment["Autore"]);
                                                                                            echo $autore["Name"] . " " . $autore["Surname"] ?></a>
            <p class="dataCommento"><?php echo $comment["DataPubblicazione"]; ?></p>
            <p><?php echo $comment["Testo"]; ?></p>
        </div>
    </div>
    <?php if (isLogged() && $_SESSION["idUser"] == $comment["Autore"]) echo '<button class="eliminaCommento" type="button" onclick="deleteComment(' . $comment["idCommento"] . ')" ><img class="bin" src="../Icons/Bin.svg" alt="cancella commento"></button>'; ?>
</li>