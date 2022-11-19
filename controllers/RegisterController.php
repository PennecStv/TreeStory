<?php


require_once(PATH_MODELS.'UserDAO.php');
use Models\UserDAO;

/**
 * This class is the registration controller.
 *
 *
 * 
 * @author  Steve Pennec   <steve.pennec@etu.univ-lyon1.fr>
 */
class RegisterController{

    /**
     * This function allows a user to register to the database
     */
    public static function register(){
        
        $userDAO = new UserDAO(strtolower($_ENV["APP_ENV"]) == "debug");

        $messageErreur = "";


        if(isset($_POST['register_bouton'])){

            extract($_POST);


            $userName        = htmlspecialchars($userName);
            $password        = htmlspecialchars($userPassword);
            $confirmPassword = htmlspecialchars($confirmUserPassword);
            $email           = htmlspecialchars($userMail);


            if(!empty($userName) && !empty($password) && !empty($confirmPassword) && !empty($email)){

                $verifUser = $userDAO->getUser($userName);

                if (!empty($verifUser)){
                    $messageErreur = "Identifiant déjà existant.";

                } else {
                    if (!($userDAO->verifPassword($password))){
                        $messageErreur = "Votre mot de passe ne respecte pas les conditions requises.";

                    } else {
                        if ($password !== $confirmPassword){
                            $messageErreur = "Le mot de passe est différent du celui ci-dessus";
                        }

                        /**
                        * Vérification E-mail
                        */

                        else {
                            $result = $userDAO->insertUser($userName, $password, $email);
                        }
                    }
                }
            }
                
            //empty fields
            else{
                $messageErreur = "Veuillez remplir tous les champs";
            }
 
        }
        
        $view = new \Templates\View("register.twig");   
        $view->render([
            "messageErreur" => $messageErreur
        ]);
    }
}
?>