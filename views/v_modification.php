<!DOCTYPE html>
<HTML>
	<HEAD>
		<meta charset="utf-8">
		<TITLE>Modification</TITLE>
	</HEAD>
	
	<BODY>
        <form action="v_modification.php" method='POST'>
            <label>Nouvel identifiant<input type = 'text' name = 'username'> </label><br>

            <label>Nouveau mot de passe <input type = 'text' name = 'password'> </label><br>

            <label>Nouvel E-Mail <input type = 'text' name = 'email'> </label><br>

            <button>Confirmer</button>
        </form>

		<ul>
		<?php

        if (isset($_POST['email']))
        {
            
            $newEmail = $_POST['email'];
            
        }
        ?>
		</ul>

	</BODY>
</HTML>