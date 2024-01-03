<!DOCTYPE html>
<html lang="it">

<head>
    <title><?php echo $templateParams["title"]; ?></title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/x-icon" href="../res/HeartHands.svg">
    <link href="../css/style.css" rel="stylesheet" type="text/css" />
    <?php
    if (isset($templateParams["css"])) {
        foreach ($templateParams["css"] as $key => $value) {
            addStyle($value);
        }
    }
    ?>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.6.0/dist/leaflet.css" integrity="sha512-xwE/Az9zrjBIphAcBb3F6JVqxf46+CDLwfLMHloNu6KEQCAWi6HcDUbeOfBIptF7tcCzusKFjFw2yuvEpDL9wQ==" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.6.0/dist/leaflet.js" integrity="sha512-gZwIG9x3wUXg2hdXF6+rVkLF/0Vi9U8D2Ntg4Ga5I5BZpVkVxlJWbSQtXPSiUTtC0TjtGOmxa1AJPuV0CPthew==" crossorigin=""></script>
    <?php
    if (isset($templateParams["js"])) :
        foreach ($templateParams["js"] as $script) :
    ?>
            <script src="<?php echo $script; ?>"></script>
    <?php
        endforeach;
    endif;
    ?>
</head>

<body>
    <header class="topbar">
        <a href="index.php" class="logoContainer">
            <img class="logo" src="../res/LogoHeart.png" alt="logo HILFE" />
        </a>
        <nav class="desktopbar">
            <a href="index.php">Home</a>
            <a href="savedPosts.php">Salvati</a>
            <a href="profile.php">Profilo</a>
            <a href="users.php">Utenti</a>
            <a href="privacy.php">Privacy</a>
            <div class="notifiche">
                <a href="notifications.php">Notifiche</a>
                <?php
                $notifications = $dbh->getNotification();
                if (count($notifications) > 0) : ?>
                    <p><?php echo count($notifications); ?></p>
                <?php endif; ?>
            </div>
        </nav>
        <a class="profiloBarra" id="profiloBarra" href="profile.php">
            <img src="../<?php echo $dbh->getSelfProfilePic(); ?>" alt="" class="fotoProfilo">
        </a>
        <div class="buttonLoginLogout">
            <?php if (isLogged()) : ?>
                <?php if ($_SERVER['REQUEST_URI'] == "/profile.php" || $_SERVER['REQUEST_URI'] == "/profile.php?id=" . $_SESSION["idUser"]) : ?>
                    <button type="button" class="b3" onclick="logout()">Logout</button>
                <?php else : ?>
                    <a href="profile.php">
                        <img src="../<?php echo $dbh->getSelfProfilePic(); ?>" alt="" class="fotoProfilo">
                    </a>
                <?php endif; ?>
            <?php else : ?>
                <a class="b1" href="login.php">Login</a>
                <a class="b2" href="profileEdit.php">Registrati</a>
            <?php endif; ?>
        </div>
        <div id="mySidepanel" class="sidepanel">
            <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
            <?php if (isLogged() && ($_SERVER['REQUEST_URI'] == "/profile.php" || $_SERVER['REQUEST_URI'] == "/profile.php?id=" . $_SESSION["idUser"])) : ?>
                    <a href="#" onclick="logout()"><img class="icon" src="../Icons/Door.svg" alt="">Logout</a>
                    <?php endif;?>
            <a href="profile.php"><img class="icon" src="../Icons/Profile.svg" alt="">Il mio profilo</a>
            <a href="privacy.php"><img class="icon" src="../Icons/Lock.svg" alt="">Privacy</a>
            <a href="savedPosts.php"><img class="icon" src="../Icons/Pin.svg" alt="">Annunci salvati</a>
            <a href="users.php"><img class="icon" src="../Icons/Profile.svg" alt="">Utenti</a>
            <div class="notifiche">
                <a href="notifications.php"><img class="icon" src="../Icons/Bell.svg" alt="">Notifiche</a>
                <?php
                if (count($notifications) > 0) : ?>
                    <p><?php echo count($notifications); ?></p>
                <?php endif; ?>
            </div>
        </div>
        <button class="openbtn" onclick="openNav()">&#9776; </button>
    </header>
    <?php
    if (isset($templateParams["page"])) {
        require($templateParams["page"]);
    }
    ?>
    <footer class="footer">
        <img src="../res/HeartHands.svg" alt="logo" />
        <div class="infoFooter">
            <p>©Hilfe 2024</p>
            <div class="linkFooter">
                <a href="privacy.php#privacy">Privacy</a>
                <a href="privacy.php#termini">Informativa cookie</a>
            </div>
        </div>
    </footer>
</body>

</html>