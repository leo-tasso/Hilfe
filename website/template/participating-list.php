<header class="titoloPagina">
            <h1>Partecipazioni</h1>
        </header>
    <div class="centralContent">
    <aside class="leftAside"> </aside>
    <main class="articles">

        <?php foreach ($dbh->getParticipatingPosts($templateParams["lastLoadedHelp"]) as $post) : ?>
            <?php include 'article.php'; ?>
        <?php endforeach; ?>
    </main>
    <?php include 'suggestions.php'; ?>
</div>