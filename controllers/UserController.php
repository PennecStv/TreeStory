<?php


require_once(PATH_MODELS.'UserDAO.php');
use Models\UserDAO;

/**
 * This class is the connection controller.
 * It retrieves the data entered in the login form and 
 * checks if there is a match with the database.
 * 
 * @author  Idrissa Sall   <idrissa.sall@etu.univ-lyon1.fr>
 */
class UserController{

    /**
     * This function allows the connection to the web site
     */
    public static function login(){
        
        $userDao = new UserDAO(strtolower($_ENV["APP_ENV"]) == "debug");

        $messageErreur = "";

        /*
        We check the sending of the connection form and we recover 
        the values ​​entered that we will compare with the data of 
        our database to authorize or not the connection to our website.
        */
        if(isset($_POST['bouton_connexion'])){
            extract($_POST);

            /*
            We retrieve the data of the two input fields.
            */
            $login_identifiant = htmlspecialchars($login_identifiant);
            $login_password = htmlspecialchars($login_password);

            /*
            We check if the two variables are not empty.
            If they are not empty, we continue the treatment.
            Otherwise, the user is told to fill in all fields with an error message.
            */
            if(!empty($login_identifiant) && !empty($login_password)){

                //We retrieve the data of the user having this UserName (ID).
                $results = $userDao->getUser($login_identifiant,null);

                /*
                We check if the variable $results is not empty.
                Which means that there is a user in our database with this UserName(ID).
                Otherwise, the user is told that this account does not exist with an error message.
                */
                if(!empty($results)){ //account exists
                    $hashpass = $results['UserPassword'];

                    /*
                    Now that we have a match for the id in our database.
                    Then we check the recovered password with the corresponding password 
                    in our database.
                    If the verification is successful, then the connection is established.
                    Otherwise, the user is told that the password or the UserName is wrong.
                    */
                    if(password_verify($login_password,$hashpass)){//the password matches
                        $_SESSION['UserName'] = $results['UserName'];

                        header('Location: /');//to change by the right link
                    }
                    //Wrong password
                    else{
                        $messageErreur = "Identifiant ou mot de passe incorrect";
                    }

                }
                //account does not exist
                else{ 
                    $messageErreur = "Le compte n'existe pas";
                }
            }
            //empty fields
            else{
                $messageErreur = "Veuillez remplir tous les champs";
            }
 
        }
        
        $view = new \Templates\View("login.twig");   
        $view->render([
            "messageErreur" => $messageErreur
        ]);
    }

    /**
     * This function allows disconnection from the site.
     */
    public static function logout(){
        $_SESSION = array();
        session_destroy();
        header('Location: /login');

    }


    
}
?>