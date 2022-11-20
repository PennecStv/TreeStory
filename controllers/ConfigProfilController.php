<?php 

require_once(PATH_MODELS.'UserDAO.php');
use Models\UserDAO;

/**
 * ModifController is the controller of the Modification page.
 * 
 * @author      Steve Pennec <steve.pennec@etu.univ-lyon1.fr>
 */
class ConfigProfilController {

    public static function configProfil(){
        $userDAO = new UserDAO(strtolower($_ENV["APP_ENV"]) == "debug");

        $messageErreur = "";
        $messageSucces = "";

        $actualUserName  = $userDAO->getUser("John")[0];
        $actualEmail     = $userDAO->getMail("John")[0];
        $actualBiography = $userDAO->getBiography("John")[0];

        $messageSucces = $actualBiography;

        $userName  = $actualUserName;
        $email     = $actualEmail;
        $biography = $actualBiography;

        if(isset($_POST['modif-button'])){
            extract($_POST);

            $userName        = htmlspecialchars($modif_userName);
            $password        = htmlspecialchars($modif_password);
            $email           = htmlspecialchars($modif_mail);
            $avatar          = htmlspecialchars($modif_Avatar);
            $biography       = htmlspecialchars($modif_Bio);


            if (!empty($userName)){
                if ($userName !== $actualUserName){
                    if (empty($userDAO->getUser($userName))){
                        $userDAO->setUserName($actualUserName, $userName);
                    }
                }
            }

            /*
            if (!empty($password)){
                if ($userName !== $actualUserName){
                    $userDAO->setPassword($actualUserName, $userName);
                    $messageSucces = "Modification effectuée avec succès!";
                }
            }
            */

            if (!empty($email)){
                if ($email !== $actualEmail){
                    $userDAO->setMail($userName, $email);
                }
            }


            if (!empty($avatar)){
                $userDAO->setAvatar($userName, $avatar);
            }

            if (!empty($biography)){
                if ($biography !== $actualBiography){
                    $userDAO->setBiography($userName, $biography);
                }
            }


            if (empty($messageErreur)){
                //  $messageSucces = "Modification effectuée avec succès!";
                //$messageSucces = $biography;
            }
        }

        

        $view = new \Templates\View("configProfil.twig");
        $view->render([
            "messageErreur" => $messageErreur,
            "messageSucces" => $messageSucces,
            "userName"      => $userName,
            "userMail"      => $email,
            "userBio"       => htmlspecialchars_decode($actualBiography) 
        ]);
    }



    /**
     * //Pas encore fini
     */
    public static function modifEmail() {
        $userDAO = new UserDAO(strtolower($_ENV["APP_ENV"]) == "debug");

        $homeDAO->setMail();
    }



    public static function modifPassword() {
        $userDAO = new UserDAO(strtolower($_ENV["APP_ENV"]) == "debug");

        $userDAO->setPassword();
    }



    public static function modifUser() {
 
    }

}

?>