<?php $user = null;
if(isLogged()){
    $user = $dbh->getUserFromId($_SESSION["idUser"]);
}
?>
<div class="centralContent">
    <main>
        <h1><?php if(!isLogged()){echo "Crea account";}else{echo "Modifica account";}?></h1>
            <form action="../utils/profileHandler.php" enctype="multipart/form-data" method="POST">
                <div class="colonne">
                    <ul class="colonna1">
                        <li class="cambiaFoto">
                            <img id="profileImage" <?php if($user!=null){echo 'src="../'.$dbh->getProfilePic($user["idUser"]).'"';}else{echo 'src="../res/profilePictures/fotoProfiloIniziale.jpg"';} ?> alt="foto profilo" />
                            <input type="file" id="fileInput" style="display: none;" onchange="loadPhoto(this)" name="profilePic">
                            <button type="button" class="modifica" onclick="document.getElementById('fileInput').click()">Modifica foto</button>
                        </li>
                        <li class="nome">
                            <label for="name">Nome:</label>
                            <input type="text" id="name" name="nome" autocomplete="given-name" required placeholder="Nome" <?php if(isLogged()){echo "value=\"".$user["Name"]."\"";}?>>
                        </li>
                        <li class="cognome">
                            <label for="surname">Cognome:</label>
                            <input type="text" id="surname" name="cognome" autocomplete="family-name" required placeholder="Cognome" <?php if(isLogged()){echo "value=\"".$user["Surname"]."\"";}?>>
                        </li>
                        <li class="username">
                            <label for="username">Username:</label>
                            <input type="text" id="username" name="username" autocomplete="username" required placeholder="Username" <?php if(isLogged()){echo "readonly value=\"".$user["Username"]."\"";}?>>
                        </li>
                        <li class="luogo">
                            <label for="indirizzo">Indirizzo:</label>
                            <input type="text" id="indirizzo" name="addr" placeholder="Indirizzo" <?php if(isLogged() && isset($user["Address"])){echo "value=\"".$user["Address"]."\"";}?>/>
                        </li>
                        <li class="data">
                            <label for="data">Data di nascita:</label>
                            <input type="date" id="data" name="data" required min="<?php echo date('Y-m-d', strtotime('-150 years')); ?>" max="<?php echo date('Y-m-d', strtotime('-18 years')); ?>" <?php if(isLogged()){echo "value=\"".$user["Birthday"]."\"";}?>>
                        </li>
                        <li class="email">
                            <label for="mail">E-mail:</label>
                            <input type="text" id="mail" name="email" required autocomplete="email" placeholder="E-mail" <?php if(isLogged()){echo "value=\"".$user["Email"]."\"";}?>>
                        </li>
                    </ul>
                    <ul class="colonna2">
                        <li class="tel">
                            <label for="tel">Cellulare:</label>
                            <input type="tel" id="tel" name="phone" placeholder="Cellulare" autocomplete="tel" minlength="10" maxlength="10" <?php if(isLogged() && isset($user["PhoneNumber"])){echo "value=\"".$user["PhoneNumber"]."\"";}?>>
                        </li>
                        <li class="bio">
                            <label for="bio">Biografia:</label><br/>
                            <input  class="textarea" type="textarea" placeholder="Biografia"  name="bio" id="bio" <?php if(isLogged() && isset($user["Bio"])){echo "value=\"".$user["Bio"]."\"";}?>>
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
                    <input class="crea" type="submit" name="Crea account" value="<?php if(!isLogged()){echo "Crea account";}else{echo "Modifica account";}?>" onclick="return validateForm()">
                </footer>
            </form>
            <?php if (isset($_GET["error"])) echo '<p class="error">'.$_GET["error"].'</p>' ?>
    </main>
</div>