<?php
$source = null;
if (isLogged()) {
    $source = $_SESSION["idUser"];
}
if (isset($_GET["id"])) {
    $source = $_GET["id"];
}
if ($source == null) {
    header('Location: ../login.php');
}
$user = $dbh->getUserFromId($source);
?>
<section class="infoUtenteProfilo">
    <div class="mainInfo">
        <header class="topInfo">
            <h1 class="nomeUtente"><?php echo $user["Name"] . " " . $user["Surname"] ?></h1>
            <input class="segui <?php if ($dbh->isFollowing($source)) {
                                    echo "seguito";
                                } ?>" type="button" name="Segui" value="<?php if (isLogged() && $source == $_SESSION["idUser"]) {
                                                                            echo "Modifica";
                                                                        } else if ($dbh->isFollowing($source)) {
                                                                            echo "Seguito";
                                                                        } else {
                                                                            echo "Segui";
                                                                        } ?>" <?php
                                                                                if (isLogged()) {
                                                                                    if ($source == $_SESSION["idUser"]) {
                                                                                        echo "onclick=\"toProfileEditPage()\"";
                                                                                    } else {
                                                                                        echo "onclick=\"toggleFollow(" . $source . ")\"";
                                                                                    }
                                                                                } else {
                                                                                    echo "onclick=\"toLoginPage()\"";
                                                                                }
                                                                                ?>>
        </header>
        <div class="bottomInfo">
            <img class="fotoProfiloPrincipale" src="../<?php echo $dbh->getProfilePic($source); ?>" alt="Foto profilo di <?php echo $user["Name"] . " " . $user["Surname"] ?>" />
            <ul>
                <li><a href="follower.php?id=<?php echo $source ?>">Followers:</a>
                    <p id="followersCount"><?php echo count($dbh->getFollower($source)); ?></p>
                </li>
                <li><a href="following.php?id=<?php echo $source ?>">Follow:</a>
                    <p id="followedCount"><?php echo count($dbh->getFollowing($source)); ?></p>
                </li>
                <li><a href="participations.php?id=<?php echo $source ?>">Partecipazioni:</a>
                    <p id="particiationsCount"><?php echo count($dbh->getParticipations($source)); ?></p>
                </li>
            </ul>
        </div>
    </div>
    <aside class="bio">
        <h2>Biografia</h2>
        <p><?php echo $user["Bio"] ?></p>
    </aside>
</section>
<div class="centralContent">
    <main class="articles">
        <?php
        foreach ($dbh->getPostsFromUser($source) as $post) : ?>
            <?php include 'article.php'; ?>
        <?php endforeach; ?>
    </main>
</div>