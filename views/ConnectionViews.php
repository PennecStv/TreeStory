<?php

/**
 * This file represents the website login form.
 * Also allows a new user to create his account.
 * 
 * @author  Idrissa Sall   <idrissa.sall@etu.univ-lyon1.fr>
 */

require_once(PATH_VIEWS.'header.php');
?>

    <div class="form-connection">
        <div class="login-card-form">
            <form method="post" enctype="multipart/form-data">
                <input type="text" name="login_identifiant" id="login_identifiant" placeholder="Entrer votre nom d'utilisateur" autocomplete="off"> <br><br>
                <input type="password" name="login_password" id="login_password" placeholder="Entrer votre mot de passe" autocomplete="off"><br><br>
                <input type="submit" name="bouton_connexion" id ="btn-connection" value="Connexion"><br><br>
            </form>
        </div>
        <?php
            if($afficheErreur){
                ?>
                    <i class="fas fa-info-circle"> <span><?= $messageErreur ?></spanp></i>
                <?php
            }
        ?>
        <div class="forgot-password">
            <a href="" title="Rénitialiser mon mot de passe">Mot de passe oublié ?</a>
        </div>

        <div class="login-card-footer">
            <span>Vous n'avez pas de compte ?</span><a href="" title="Je m'inscris">&nbspInscrivez-vous</a>
        </div>

    </div>


<?php 

require_once(PATH_VIEWS.'footer.php');
?>