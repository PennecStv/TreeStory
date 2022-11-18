<?php 

require(PATH_MODELS.'HomeDAO.php');
use Models\HomeDAO;

/**
 * ModifController is the controller of the Modification page.
 * 
 * @author      Steve Pennec <steve.pennec@etu.univ-lyon1.fr>
 */
class ModifController {

    /**
     * //Pas encore fini
     */
    public static function modifEmail() {
        $userDAO = new UserDAO(strtolower($_ENV["APP_ENV"]) == "debug");

        $view = new \Templates\View("v_modification.php");   
        
        $homeDAO->setMail();
    }



    public static function modifPassword() {
        $userDAO = new UserDAO(strtolower($_ENV["APP_ENV"]) == "debug");

        $view = new \Templates\View("v_modification.php");  

        $userDAO->setPassword();
    }



    public static function modifUser() {
 
    }

}

?>