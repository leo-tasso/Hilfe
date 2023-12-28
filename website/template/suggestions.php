    <aside class="rightAside">
        <h2>Profili suggeriti</h2>
        <?php foreach ($dbh->getSuggestedUsers(START_SUGGESTED_USERS) as $user) : ?>
            <?php require 'profilePreview.php'; ?>
        <?php endforeach; ?>
    </aside>
