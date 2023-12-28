<div class="centralContent">
    <aside class="leftAside"></aside>
    <main class="followers_seguiti">
        <h1>Seguaci</h1>
        <input id="search" class="search" type="search" placeholder="Cerca...">
        <ul id="elencoFollowers_seguiti" class="elencoFollowers_seguiti">
            <?php
            $followingIds = array_column($dbh->getFollower(isset($_GET["id"]) ? $_GET["id"] : 1), 'idSeguito');
            foreach ($dbh->addDescription($followingIds) as $user) : ?>
                <li class="follower"><a href="profiloUtente.php?id=<?php echo $user["idUser"] ?>"><img src="../<?php echo $dbh->getProfilePic($user["idUser"]); ?>" alt="Foto profilo">
                        <div class="infoUtente"><span><?php echo $user["Name"] . " " . $user["Surname"] ?></span>
                            <p class="testo">
                                <?php if (isset($user["seiTu"])) {
                                    echo "Sei Tu <3";
                                }
                                if (isset($user["seguace"])) {
                                    echo "Ti Segue";
                                }
                                if (isset($user["seguace"]) && isset($user["NumSeguitiInComune"])) {
                                    echo " - ";
                                }
                                if (isset($user["NumSeguitiInComune"])) {
                                    echo $user["NumSeguitiInComune"] . " Seguiti in comune";
                                }
                                if (isset($user["Motivazione"])) {
                                    echo $user["Motivazione"];
                                } ?>
                            </p>
                        </div>
                    </a></li>
            <?php endforeach; ?>
        </ul>
    </main>
    <?php include 'suggestions.php'; ?>
</div>