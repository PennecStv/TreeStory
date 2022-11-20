<?php 

require_once(PATH_MODELS.'UserDAO.php');
use Models\UserDAO;

/**
 * ModifController is the controller of the Modification page.
 * 
 * @author      Steve Pennec <steve.pennec@etu.univ-lyon1.fr>
 */
class ModifProfilController {

    public static function modification(){
        $userDAO = new UserDAO(strtolower($_ENV["APP_ENV"]) == "debug");

        $messageErreur = "";

        if(isset($_POST['register-button'])){

            extract($_POST);

        }


        $view = new \Templates\View("modification.twig");
        $view->render([
            "messageErreur" => $messageErreur
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