<div class="centralContent">
    <main>
        <h1>Crea account</h1>
        <section>
            <form action="../utils/registrationHandler.php" enctype="multipart/form-data" method="POST">
                <div class="colonne">
                    <ul class="colonna1">
                        <li class="cambiaFoto">
                            <img id="profileImage" src="../res/profilePictures/fotoProfiloIniziale.jpg" alt="foto profilo" />
                            <input type="file" id="fileInput" style="display: none;" onchange="loadPhoto(this)" name="profilePic">
                            <button type="button" class="modifica" onclick="document.getElementById('fileInput').click()">Modifica foto</button>
                        </li>
                        <li class="name">
                            <label for="name">Nome:</label>
                            <input type="text" id="name" name="nome" autocomplete="given-name" required placeholder="Nome">
                        </li>
                        <li class="surname">
                            <label for="surname">Cognome:</label>
                            <input type="text" id="surname" name="cognome" autocomplete="family-name" required placeholder="Cognome">
                        </li>
                        <li class="username">
                            <label for="username">Username:</label>
                            <input type="text" id="username" name="username" autocomplete="username" required placeholder="Username">
                        </li>
                        <li class="date">
                            <label for="data">Data di nascita:</label>
                            <input type="date" id="data" name="data" required placeholder="Data di nascita" min="<?php echo date('Y-m-d', strtotime('-150 years')); ?>" max="<?php echo date('Y-m-d', strtotime('-18 years')); ?>">
                        </li>
                    </ul>
                    <ul class="colonna2">
                        <li class="email">
                            <label for="mail">E-mail:</label>
                            <input type="text" id="mail" name="email" required autocomplete="email" placeholder="E-mail">
                        </li>
                        <li class="pass">
                            <label for="password">Password:</label>
                            <input type="password" id="password" name="password" required placeholder="Password">
                        </li>
                        <li class="pass">
                            <label for="conferma">Conferma Password:</label>
                            <input type="password" id="conferma" name="conferma password" required placeholder="Conferma password">
                        </li>
                    </ul>
                </div>
                <footer class="buttonsAndConditions">
                    <div class="conditions">
                        <input id="autorizzazione" type="checkbox" name="autorizzazione privacy" value="Autorizzazione privacy" />
                        <label for="autorizzazione"><a href="privacy.html">Accetto le condizioni, i termini d'uso e l'informativa per la privacy</a></label>
                    </div>
                    <?php if (isset($_GET["error"])) {
                        echo '<p class="error">' . $_GET["error"] . "</p>";
                    } ?>
                    <input class="crea" type="submit" name="Crea account" value="Crea account" onclick="return validateForm()">
                </footer>
            </form>
        </section>
    </main>
</div>