<?php include 'map.php'; ?>
<header class="newPostContainer">
    <div class="navbar">
        <a class="default" href="#"><img class="icon" src="../Icons/Lens.svg" alt="">Esplora</a>
        <a href="#"><img class="icon" src="../Icons/Profile.svg" alt="">Followers</a>
    </div>
    <div class="newPost">
        <img src="../<?php echo $dbh->getSelfProfilePic(); ?>" alt="Profilo 1" class="profilo">
        <a href="creaPost.html">Hai bisogno di aiuto?🛟 Nuovo post</a>
    </div>
</header>
<div class="centralContent">
    <aside class="leftAside">

        <h2>Raggio di ricerca</h2>
        <div class="partenzaContainer">
            <label for="partenza">Da:</label>
            <input type="text" id="partenza" placeholder="<?php echo $dbh->getAddress()["Address"] ?>" />
        </div>
        <div class="rangeContainer">
            <label for="range" hidden>Distanza</label>
            <input id="range" type="range" min="0" max="100" />
            <span id="rangeValue">50km</span>
        </div>
        <button type="button" class="vai">Vai</button>
        <script>
            // Aggiungi un gestore di eventi per aggiornare il numero accanto alla barra di intervallo
            const rangeInput = document.getElementById('range');
            const rangeValue = document.getElementById('rangeValue');

            rangeInput.addEventListener('input', function() {
                rangeValue.textContent = rangeInput.value == 100 ? "Illimit." : rangeInput.value + "km";
            });
        </script>
    </aside>
    <main class="articles">

        <?php foreach ($dbh->getHelpPosts($START_POST_NUMBER, 0, 0, 0, 100) as $post) : ?>
            <?php include 'article.php'; ?>
        <?php endforeach; ?>

        <button type="button" class="buttonAltriPost">Altri Post</button>
    </main>
    <aside class="rightAside">
        <h2>Profili suggeriti</h2>
        <?php foreach ($dbh->getSuggestedUsers(START_SUGGESTED_USERS) as $user) : ?>
            <?php require 'profilePreview.php'; ?>
        <?php endforeach; ?>
    </aside>
</div>