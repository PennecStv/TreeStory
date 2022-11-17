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
     * Computes the "home" request into a response.
     */
    public static function modifUser() {
        $userDAO = new UserDAO(strtolower($_ENV["APP_ENV"]) == "debug");

        $view = new \Templates\View("v_modification.php");   
        
    }



    public static function modifPassword() {
        $homeDAO = new HomeDAO(strtolower($_ENV["APP_ENV"]) == "debug");

        $view = new \Templates\View("home.twig");   
        $view->render([
            "nbInscrit" => htmlspecialchars($homeDAO->getNbInscrit()),
            "nbStoryCreate" => htmlspecialchars($homeDAO->getNbStory())
        ]);
    }


    
    public static function modifEmail() {
        $homeDAO = new HomeDAO(strtolower($_ENV["APP_ENV"]) == "debug");

        $view = new \Templates\View("home.twig");   
        $view->render([
            "nbInscrit" => htmlspecialchars($homeDAO->getNbInscrit()),
            "nbStoryCreate" => htmlspecialchars($homeDAO->getNbStory())
        ]);
    }

}

?>