<a href="profiloUtente.php/<?php echo $user["idUser"] ?>" class="infoUser">
            <img src="../<?php echo $dbh->getProfilePic($user["idUser"]);?>" alt="immagine profilo di <?php echo $user["Name"] ?> <?php echo $user["Surname"] ?>">
            <div class="profile">
                <span><?php echo $user["Name"] ?> <?php echo $user["Surname"] ?></span>
                <p class="amici"><?php 
                if(isset($user["seiTu"])){ echo "Sei Tu <3";} 
                if(isset($user["seguace"])){ echo "Ti Segue";} 
                if(isset($user["seguace"]) && isset($user["NumSeguitiInComune"])){ echo " - ";}
                if(isset($user["NumSeguitiInComune"])){ echo $user["NumSeguitiInComune"]." Seguiti in comune";}
                if(isset($user["Motivazione"])){ echo $user["Motivazione"];}
                ?></p>
            </div>
        </a>