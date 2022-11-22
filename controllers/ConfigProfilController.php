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

        //Replace "John" with the loged User
        $actualUserName  = $userDAO->getUser("John")[0];
        $actualEmail     = $userDAO->getMail("John")[0];
        $actualAvatar    = $userDAO->getAvatar("John")[0];
        $actualBiography = $userDAO->getBiography("John")[0];

        $userName  = $actualUserName;
        $email     = $actualEmail;
        $avatar    = $actualAvatar;
        $biography = $actualBiography;

        if (empty($avatar)){
            $avatar = "./assets/images/user.png";
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            

            if(isset($_POST['modif_button'])){
                //extract($_POST);

                $userName        = htmlspecialchars($modif_userName);
                //$password        = htmlspecialchars($modif_password);
                $email           = htmlspecialchars($modif_mail);
                $avatar          = htmlspecialchars($modif_avatar);
                $biography       = htmlspecialchars($modif_bio);


                if (!empty($userName)){
                    if ($userName !== $actualUserName){
                        if (empty($userDAO->getUser($userName))){
                            $userDAO->setUserName($actualUserName, $userName);
                        }
                    }
                }

                
                if (!empty($password)){
                        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                        $userDAO->setPassword($userName, $hashedPassword);
                }
                

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
                    $messageSucces = "Modification effectuée avec succès!";

                }
            }


            else {
                $userName        = "";
                $email           = "";
                $avatar          = "";
                $biography       = "";

                $userDAO->deleteUser($actualUserName);
                $messageSucces = "Utilisateur supprimé !";
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