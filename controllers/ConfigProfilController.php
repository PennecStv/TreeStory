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

        //Retrieve the actual data of the user in order to save them for configuration and show the actual data on the page
        $results = $userDAO->getUser("John", null);
        $actualUserName  = $results['UserName'];
        $actualEmail     = $results['UserMail'];
        $actualAvatar    = $results['UserAvatar'];
        $actualBiography = $results['UserBiography'] ;

        //Giving the actual data to variables that we will use for the configuration
        $userName  = $actualUserName;
        $email     = $actualEmail;
        $avatar    = $actualAvatar;
        $biography = $actualBiography;

        //Set a default User Picture
        if (empty($avatar)){
            $avatar = "./assets/images/user.png";
        }

        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            //If the user click on the modification button
            if (isset($_POST['modif_button'])){
                extract($_POST);

                $userName        = htmlspecialchars($modif_userName);
                $email           = htmlspecialchars($modif_mail);
                $avatar          = htmlspecialchars($modif_avatar);
                $biography       = htmlspecialchars($modif_bio);

                //Setting all the modification on the database
                if (!empty($userName)){
                    if ($userName !== $actualUserName){
                        if (empty($userDAO->getUser($userName,null))){
                            $userDAO->setUser('UserName', $userName, $actualUserName);
                        }
                    }
                }
                 
                /*
                if (!empty($password)){
                        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                        $userDAO->setUser($userName, $hashedPassword);
                }
                */

                if (!empty($email)){
                    if ($email !== $actualEmail){
                        $userDAO->setUser('UserMail', $email, $userName);
                    }
                }

                if (!empty($avatar)){
                    $userDAO->setUser('UserAvatar', $avatar, $userName);
                }

                if (!empty($biography)){
                    if ($biography !== $actualBiography){
                        $userDAO->setUser('UserBiography', $biography, $userName);
                    }
                }

                $messageSucces = "Modification effectuée avec succès!";
            }
            

            //If the user click on the confirm button of the DELETE USER Modal
            if (isset($_POST['confirm_button'])){
                extract($_POST);
                $hashpass = $results['UserPassword'];

                //Verify the password for safety
                if(password_verify($confirm_password,$hashpass)){
                    $userName        = "";
                    $email           = "";
                    $avatar          = "";
                    $biography       = "";

                    $userDAO->deleteUser($actualUserName);
                    $messageSucces = "Utilisateur supprimé !";
                }
                //Wrong password
                else{
                    $messageErreur = "Mot de passe incorrect";
                }
            }
        }


        $view = new \Templates\View("configProfil.twig");
        $view->render([
            "messageErreur" => $messageErreur,
            "messageSucces" => $messageSucces,
            "userName"      => $userName,
            "userMail"      => $email,
            "userAvatar"    => $avatar,
            "userBio"       => $actualBiography
        ]);
    }
}

?>