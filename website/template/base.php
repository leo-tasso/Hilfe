<!DOCTYPE html>
<html lang="it">

<head>
    <title><?php echo $templateParams["title"]; ?></title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/x-icon" href="../res/HeartHands.svg">
    <link href="../css/style.css" rel="stylesheet" type="text/css" />
    <?php
    if(isset($templateParams["css"])){
        require($templateParams["css"]);
    }
    ?>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.6.0/dist/leaflet.css"
        integrity="sha512-xwE/Az9zrjBIphAcBb3F6JVqxf46+CDLwfLMHloNu6KEQCAWi6HcDUbeOfBIptF7tcCzusKFjFw2yuvEpDL9wQ=="
        crossorigin="" />
    <!-- TODO extract scripts -->
    <script>
        /* Set the width of the sidebar to 250px (show it) */
        function openNav() {
            document.getElementById("mySidepanel").style.width = "250px";
        }
        /* Set the width of the sidebar to 0 (hide it) */
        function closeNav() {
            document.getElementById("mySidepanel").style.width = "0";
        }
    </script>
    <script src="https://unpkg.com/leaflet@1.6.0/dist/leaflet.js"
        integrity="sha512-gZwIG9x3wUXg2hdXF6+rVkLF/0Vi9U8D2Ntg4Ga5I5BZpVkVxlJWbSQtXPSiUTtC0TjtGOmxa1AJPuV0CPthew=="
        crossorigin=""></script>
</head>

<body>
    <header class="topbar">
            <a href="home.html" class="logoContainer">
                <img class="logo" src="../res/LogoHeart.svg" alt="logo HILFE" />
            </a>
            <nav class="desktopbar">
                <a href="home.html">Home</a>
                <a href="annunciSalvati.html">Salvati</a>
                <a href="profiloUtente.html">Profilo</a>
                <a href="privacy.html">Privacy</a>
                <div class="notifiche">
                    <a href="notifiche.html">Notifiche</a>
                    <p>1</p>
                    <!--TODO update notification number -->
                </div>
            </nav>
            <a class="profiloBarra" id="profiloBarra" href="profiloUtente.html"> </a>
            <!--TODO profpic if logged -->
            <div class="buttonLogin">
                <a class="b1" href="login.html">Login</a>
                <a class="b2" href="registrazione.html">Registrati</a>
            </div>
            <div id="mySidepanel" class="sidepanel">
                <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
                <a href="login.html"><img class="icon" src="../Icons/Door.svg" alt="">Login</a>
                <a href="profiloUtente.html"><img class="icon" src="../Icons/Profile.svg" alt="">Il mio profilo</a>
                <a href="privacy.html"><img class="icon" src="../Icons/Lock.svg" alt="">Privacy</a>
                <a href="annunciSalvati.html"><img class="icon" src="../Icons/Pin.svg" alt="">Annunci salvati</a>
                <div class="notifiche">
                    <a href="notifiche.html"><img class="icon" src="../Icons/Bell.svg" alt="">Notifiche</a>
                    <p>1</p>
                </div>
            </div>
            <button class="openbtn" onclick="openNav()">&#9776; </button>
    </header>
    <?php
    if(isset($templateParams["page"])){
        require($templateParams["page"]);
    }
    ?>
        <footer class="footer">
        <p>Progetto di Tecnologie Web - A.A. 2023/2024</p>
    </footer>
    <?php
    if(isset($templateParams["js"])):
        foreach($templateParams["js"] as $script):
    ?>
        <script src="<?php echo $script; ?>"></script>
    <?php
        endforeach;
    endif;
    ?>
</body>
</html>