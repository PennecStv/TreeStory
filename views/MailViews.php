<?php

/**
 * This file is the view of the email.
 * this is the document that is sent to 
 * the user allowing him to reset his password.
 * 
 * @author  Idrissa Sall   <idrissa.sall@etu.univ-lyon1.fr>
 */
    $page_for_reset = "treestory.local/resetPassword&token=$token";
    $to = $email;
    $subject = 'Réinitialisation de votre mot de passe';
    $message ='
    <html>
    <head>
        <title>Réinitialiser mot de passe</title>
        <style>
            p,h1 {
                color: black;
                text-align:left;
            }
            a{
                text-decoration: none;
                align-self : center;
            }
        </style>
    </head>

    <body>
        <h1>Réinitialisation de votre mot de passe</h1>
        <p>Bonjour '.$login_forgot_password.',</p>
        <p>Vous avez oublié votre mot de passe ?</p>
        <p>Réinitialisons le afin que vous puissiez continuer à lire des histoires sur TreeStory, cliquez sur ce lien:</p>
        <a href='.$page_for_reset.'>Réinitialiser mon mot de passe</a>
        <p>Ce lien est valable 24 heures. Passé ce délai, vous devrez renouveler votre demande sur le site.</p>

    </body>

    </html>
    ';

    $headers[] = 'MIME-Version: 1.0';
    $headers[] = 'Content-Type:text/html; charset="utf-8"';
    $headers[] = 'Content-Transfer-Encoding: 8bit';
    //additonnels
    $headers[] = 'From: TreeStory <idrissa.sall@etu.univ-lyon1.fr>';
    $headers[] = 'Reply-To: idrissa.sall@etu.univ-lyon1.fr';

    if(mail($to, $subject, $message, implode("\r\n", $headers))){
        date_default_timezone_set('Europe/Paris');
        $date = date("Y-m-d H:i:s");
        $tomorrow  = mktime(date("H")+24, date("i"), date("s"), date("m") , date("d"), date("Y"));

        $userDao->setUser("UserTokenExpirationAt",date("Y-m-d H:i:s",$tomorrow),$login_forgot_password);
        $userDao->setUser("UserToken",$token,$login_forgot_password);

        $messageErreur = "Veuiller vérifier vos e-mails pour réinitialiser votre mot de passe.";
    }
    else{
        $messageErreur = "Un problème est survenu lors de réinitialisation de votre mot de passe.";
    }

?>