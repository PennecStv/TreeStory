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
        $messageSucces = "";


        if(isset($_POST['register-button'])){

            //Extracting the data the user put in the register form.
            extract($_POST);

            $userName        = htmlspecialchars($userName);
            $password        = htmlspecialchars($userPassword);
            $confirmPassword = htmlspecialchars($confirmUserPassword);
            $email           = htmlspecialchars($userMail);

            /* Checking if all the fields aren't empty */
            if(!empty($userName) && !empty($password) && !empty($confirmPassword) && !empty($email)){

                //Verify if the userName does already exist
                $verifUser = $userDAO->getUser($userName,null);
                if (!empty($verifUser)){
                    $messageErreur = "Identifiant déjà existant.";

                } else {
                    //Hashing the password before sending it to the database for security reason
                    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                    $result = $userDAO->insertUser($userName, $hashedPassword, $email);

                    //Account created
                    $_SESSION['UserName'] = $userName;
                    header('Location: /logout'); //Redirect to home page
                }
            }
                
            //Empty fields
            else{
                $messageErreur = "Veuillez remplir tous les champs";
            }
        }
        

        $view = new \Templates\View("register.twig");   
        $view->render([
            "messageErreur" => $messageErreur,
            //"messageSucces" => $messageSucces
        ]);
    }
}
?>