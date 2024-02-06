<header class="newPostContainer">
    <div class="navbar">
        <a class="default" href="#"><img class="icon" src="../Icons/Lens.svg" alt="">Esplora</a>
        <a href="homeFollow.php"><img class="icon" src="../Icons/Profile.svg" alt="">Seguiti</a>
    </div>
    <div class="newPost">
        <img src="../<?php echo $dbh->getSelfProfilePic(); ?>" alt="Profilo 1" class="profilo">
        <a href="postHelpEdit.php">Hai bisogno di aiuto?🛟 Nuovo post</a>
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
            <input id="range" type="range" min="0" max="100" value="100" />
            <span id="rangeValue">Illimit.</span>
        </div>
        <button type="button" class="vai" onclick="updatePosts()">Vai</button>
        <div>
            <p>"Advertisement placeholder"</p>
        </div>
    </aside>
    <main class="articles">

        <?php foreach ($dbh->getHelpPosts($START_POST_NUMBER, $templateParams["lastLoaded"], $templateParams["lat"], $templateParams["long"], $templateParams["range"]) as $post) : ?>
            <?php include 'article.php'; ?>
        <?php endforeach; ?>

        <button type="button" class="buttonAltriPost" onclick="morePosts()">Altri Post</button>
    </main>
    <?php include 'suggestions.php'; ?>
</div>