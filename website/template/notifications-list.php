<div class="centralContent">
    <aside class="leftAside"></aside>
    <main class="notificheMain">
        <h1>Notifiche</h1>
        <ul class="elencoNotifiche">
            <?php foreach ($dbh->getNotifications() as $notification) : ?>
                <li class="notifica <?php if($notification["Letta"]==0) {echo "\" \"daLeggere";}?>"><img src="../<?php echo $dbh->getProfilePic($notification["idUserGeneratore"]); ?>" alt="Profilo <?php echo $notification["idUserGeneratore"];?>">
                    <div class="infoNotifica"><span><?php $userCreator = $dbh->getUserFromId($notification["idUserGeneratore"]); echo $userCreator["Name"]." ".$userCreator["Surname"]?></span>
                        <p class="testo"><?php echo $notification["TestoNotifica"];?></p>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    </main>
    <?php include 'suggestions.php'; ?>
</div>