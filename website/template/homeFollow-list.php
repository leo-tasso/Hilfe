<header class="newPostContainer">
        <div class="navbar">
            <a href="index.php"><img class="icon" src="../Icons/Lens.svg" alt="" >Esplora</a>
            <a class="default" href="#"><img class="icon" src="../Icons/Profile.svg" alt="">Followers</a>
        </div>
        <div class="newPost">
            <img src="../<?php echo $dbh->getSelfProfilePic(); ?>" alt="Profilo 1" class="profilo">
            <a href="postInfoEdit.php">Vuoi condividere un pensiero? ✍ Crea post</a>
        </div>
        </header>
    <div class="centralContent">
    <aside class="leftAside"></aside>
    <main class="articles">
        <?php foreach ($dbh->getInfoPosts($START_POST_NUMBER, $templateParams["lastLoadedInfo"],$_SESSION["idUser"]) as $post) : ?>
            <?php include 'article.php'; ?>
        <?php endforeach; ?>

        <button type="button" class="buttonAltriPost" onclick="moreInfoPosts()">Altri Post</button>
    </main>
    <?php include 'suggestions.php'; ?>
</div>