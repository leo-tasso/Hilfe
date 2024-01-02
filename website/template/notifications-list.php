<div class="centralContent">
    <aside class="leftAside"></aside>
    <main class="notificheMain">
        <h1>Notifiche</h1>
        <ul class="elencoNotifiche">
            <?php foreach ($dbh->getNotifications() as $notification) : ?>
                <li class="notifica <?php if ($notification["Letta"] == 0) {
                                        echo "\" \"daLeggere";
                                    } ?>"><img src="../<?php echo $dbh->getProfilePic($notification["idUserGeneratore"]); ?>" alt="Profilo <?php echo $notification["idUserGeneratore"]; ?>">
                    <div class="infoNotifica"><span><?php $userCreator = $dbh->getUserFromId($notification["idUserGeneratore"]);
                                                    echo $userCreator["Name"] . " " . $userCreator["Surname"] ?></span>
                        <p><?php echo $notification["DataCreazione"]; ?></p>
                        <p class="testo"><?php echo $notification["TestoNotifica"]; ?></p>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
        <?php if (count($dbh->getNotifications()) == 0) : ?>
            <p class="warning">Nessuna notifica</p>
        <?php endif; ?>
    </main>
    <?php include 'suggestions.php'; ?>
</div>