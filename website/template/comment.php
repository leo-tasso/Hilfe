<li class="commento">
    <a href="profile.php?id=<?php echo $comment["Autore"];?>"><img class="profilo" src="../<?php echo $dbh->getProfilePic($comment["Autore"]); ?>" alt="foto profilo autore"></a>
    <div class="infoCommento">
        <a href="profile.php?id=<?php echo $comment["Autore"];?>" class="nomeAutore"><?php $autore = $dbh->getUserFromId($comment["Autore"]);
                                                            echo $autore["Name"] . " " . $autore["Surname"] ?></a>
        <p><?php echo $comment["Testo"];?></p>
    </div>
</li>