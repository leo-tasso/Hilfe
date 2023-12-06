<!DOCTYPE html>
<html lang="it">
    <head>
    <title><?php echo $templateparams["title"]?></title>    
    <meta charset="UTF-8"/>
        <!-- link to add favicon-->
        <link rel="icon" type="image/x-icon" href="../LogoRes/1x/LogoNuovo.png">
        <link href="../css/style.css" rel="stylesheet" type="text/css"/>
        <link href="../css/styleHome.css" rel="stylesheet" type="text/css"/>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <script>
            /* Set the width of the sidebar to 250px (show it) */
            function openNav() {
            document.getElementById("mySidepanel").style.width = "250px";
            }

            /* Set the width of the sidebar to 0 (hide it) */
            function closeNav() {
            document.getElementById("mySidepanel").style.width = "0";
            }
        </script> 
    </head>
    <body>
        <header> 
            <nav class="top bar">
                <img src="../LogoRes/1x/LogoHeart.png" alt="logo HILFE"/>
                <div id="mySidepanel" class="sidepanel">
                    <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
                    <a href="home.html">Home</a>  
                    <a href="profiloUtente.html">Profilo</a>
                    <a href="esplora.html">Esplora</a>
                </div>
                <button class="openbtn" onclick="openNav()" >&#9776; </button>
            </nav> 
            <h1>Home page</h1>
            <div class="navbar">
                <a href="#"><i class="fa fa-fw fa-search"></i> Esplora</a>
                <a href="#"><i class="fa fa-fw fa-user"></i> Followers</a>
              </div>
            <ul>
                <li><p>Hai bisogno di aiuto?</p></li>
                <li><a href="creaPost.html"><i class="fa fa-fw fa-envelope"></i> Nuovo post</a></li>
            </ul>
        </header>
        <main>
        <?php
foreach($templateparams["articles"] as $article): ?>
            <article>
                <header>
                    <h2><?php echo $articolo["Titolo"]; ?></h2>
                    <p><?php echo $articolo["DataPubblicazione"]; ?></p>
                </header>
                <section>
                    <p>
                    <?php echo $articolo["Testo"]; ?>
                </p>
                </section>
                <div class="google-maps">
                   <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d91611.09494987623!2d12.153389202559785!3d44.148405065489904!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x132ca58ba97cf34f%3A0x9a4e66c64fd8978c!2sCampus%20di%20Cesena%20-%20Universit%C3%A0%20di%20Bologna%20-%20Alma%20Mater%20Studiorum!5e0!3m2!1sit!2sit!4v1700418585005!5m2!1sit!2sit" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe> 
                </div>
                <footer>
                    
                        <input type="button" name="Salva" value="Salva">
                        <input type="button" name="Partecipa" value="Partecipa">
                        <a href="elencoPartecipanti.html">Partecipanti</a>
                      
                </footer>
            </article>
            <?php
endforeach;
?>
        </main><aside>
        </aside>
        <footer>
            <p>Progetto di Tecnologie Web - A.A. 2023/2024</p>
        </footer>
    </body>
</html>
