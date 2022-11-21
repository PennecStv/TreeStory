<?php

require_once(PATH_MODELS.'UserDAO.php');
use Models\UserDAO;

/**
 * This class is the password controller.
 * It alows to reset the password.
 * 
 * @author  Idrissa Sall   <idrissa.sall@etu.univ-lyon1.fr>
 */
class PasswordController{

    /**
     * This function retrieves the username entered 
     * in the login form and 
     * checks if there is a match with the database and
     * and send an email to the mail corresponding.
     */
    public static function forgotPassword(){

        $userDao = new UserDAO(strtolower($_ENV["APP_ENV"]) == "debug");

        $messageErreur = "";

        /*
        We check the sending of the reset form and we recover 
        the username ​​entered that we will compare with the data of 
        our database to authorize or not the reset of pasword.
        */
        if(isset($_POST['bouton_forgot_password'])){

            extract($_POST);

            /*
            We retrieve the data of the input field.
            */
            $login_forgot_password = htmlspecialchars($login_forgot_password);

            /*
            We check if the login_forgot_password variable is not empty.
            If is is not empty, we continue the treatment.
            Otherwise, the user is told to fill in the field with an error message.
            */
            if(!empty($login_forgot_password)){

                //We retrieve the data of the user having his UserName (ID).
                $results = $userDao->getUser($login_forgot_password,null);

                /*
                We check if the variable $results is not empty.
                Which means that there is a user in our database with this UserName(ID).
                Otherwise, the user is told that this account does not exist with an error message.
                */
                if(!empty($results)){ // the account exists
                    $email = $results['UserMail'];
                    $token = $userDao -> getRandomToken();
                    require_once(PATH_VIEWS.'MailViews.php');

                }
                //account does not exist
                else{ 
                    $messageErreur = "Il n'existe pas de compte associé à ce nom d'utilisateur.";
                }
            }
            //empty field
            else{
                $messageErreur = "Veuillez saisir votre nom d'utilisateur.";
            }

        }

        $view = new \Templates\View("forgotPassword.twig");   
        $view->render([
            "messageErreur" => $messageErreur
        ]);

    }


    /**
     * This function set the new password in our
     * database.
     */
    public static function resetPassword(){

        $userDao = new UserDAO(strtolower($_ENV["APP_ENV"]) == "debug");

        $messageErreur = "";

        /*
        We retrive the token from the URL and the user with
        this token in our database.
        */
        $tokenFromURL = htmlspecialchars($_GET['token']);
        $results = $userDao -> getUser($tokenFromURL,"token");

        date_default_timezone_set('Europe/Paris');
        $currentDate = date("Y-m-d H:i:s");

        /*
        We check if we have a match in our database with this token.
        It means that the variable $results is not empty.
        And ($_GET['token']) is set.
        */
        if(!empty($results) && !empty($tokenFromURL)){

            $tokenFromBDD = $results['UserToken'];
            $expirationDate = $results['UserTokenExpirationDate'];

            /*
            We check if the token in the URL has match in our data base
            and the current date dose not exceed the expiration date.
            */
            if($tokenFromURL == $tokenFromBDD && ($currentDate <= $expirationDate) > 0){

                /*
                We check if the bouton_reset_password is set and the token 
                in the URL is still the same with the token in our database.
                */
                if(isset($_POST['bouton_reset_password']) && $tokenFromURL == $results['UserToken']){

                    /*
                    We retrieve the data of the input field.
                    */
                    $new_mdp = htmlspecialchars($_POST['new_mdp']);
                    
                    $options = [
                        'cost' => 12,
                    ];
            
                    $hash = password_hash($new_mdp,PASSWORD_BCRYPT,$options);

                    /*
                    We change the old password with the new.
                    */
                    $userDao->setUser("UserPassword",$hash,$results['UserName']);

                    /*
                    We change the UserToken and UserTokenExpirationDate colmun 
                    to their default value.
                    */
                    $userDao->setUser("UserToken",null,$results['UserName']);
                    $userDao->setUser("UserTokenExpirationDate",date("Y-m-d H:i:s",0),$results['UserName']);

                    $messageErreur ="Votre nouveau mot de passe a bien été enregistré.";
                }

                $view = new \Templates\View("resetPassword.twig");   
                $view->render([
                    "messageErreur" => $messageErreur
                ]);
            }
        }
        //redirection to the expiration page or page erreur.
        else{
            header('Location: /404');
        }

        
    }
}

?>