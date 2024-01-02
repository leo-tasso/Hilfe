<header class="titoloPagina">
            <h1>Annunci salvati</h1>
        </header>
    <div class="centralContent">
    <aside class="leftAside"> </aside>
    <main class="articles">

        <?php foreach ($dbh->getSavedPosts() as $post) : ?>
            <?php include 'article.php'; ?>
        <?php endforeach; ?>
    </main>
    <?php include 'suggestions.php'; ?>
</div>