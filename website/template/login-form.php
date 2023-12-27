<main>
    <section>
        <header>
            <img class="logo2" src="../res/HeartHands.svg" alt="logo HILFE" />
        </header>
        <form class="form" action="../utils/loginHandler.php" method="POST">
            <ul>
                <li><label for="name">Username</label><input type="text" id="name" name="name" placeholder="Username"></li>
                <li><label for="password">Password</label><input type="password" id="password" name="password" placeholder="Password"></li>
            </ul>
            <div class="options">
                <a href="#" class="dimenticata">Password dimenticata?</a><br />
                <div class="ricordami">
                    <input type="checkbox" id="ricordami" name="ricordami" value="Ricordami"><label for="ricordami">Ricordami</label>
                </div>
            </div>
            <footer>
                <a class="log" href="login.html">Login</a>
                <p>oppure</p>
                <a class="registrati" href="registrazione.html">Registrati</a>
            </footer>
        </form>
    </section>
</main>