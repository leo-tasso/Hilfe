<div class="centralContent">
    <aside class="leftAside"></aside>
    <main class="followers_seguiti">
        <h1>Utenti</h1>
        <input id="search" class="search" type="search" placeholder="Cerca...">
        <ul id="elencoFollowers_seguiti" class="elencoFollowers_seguiti">
            <?php
            $users = array_column($dbh->getAllUsers(), 'idUser');
            foreach ($dbh->addDescription($users) as $user) : ?>
                <li class="follower"><a href="profile.php?id=<?php echo $user["idUser"] ?>"><img src="../<?php echo $dbh->getProfilePic($user["idUser"]); ?>" alt="Foto profilo">
                        <div class="infoUtente"><span><?php echo $user["Name"] . " " . $user["Surname"] ?></span>
                            <p class="testo">
                                <?php
                                echo $user["Username"] . ", " . $user["Email"]. " ";
                                if (isset($user["seiTu"])) {
                                    echo "Sei Tu <3";
                                }
                                 ?>
                            </p>
                        </div>
                    </a></li>
            <?php endforeach; ?>
        </ul>
    </main>
    <?php include 'suggestions.php'; ?>
</div>