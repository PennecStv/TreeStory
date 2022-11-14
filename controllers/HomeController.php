<?php 

require(PATH_MODELS.'HomeDAO.php');
use Models\HomeDAO;

/**
 * HomeController is the controller of the home page.
 * 
 * @author      Jonathan Montmain <jonathan.montmain@etu.univ-lyon1.fr>
 * @author      Rudy Boullier <rudy.boullier@etu.univ-lyon1.fr>
 */
class HomeController {

    /**
     * Computes the "home" request into a response.
     */
    public static function home() {
        $homeDAO = new HomeDAO(strtolower($_ENV["APP_ENV"]) == "debug");

        $view = new \Templates\View("home.twig");   
        $view->render([
            "nbInscrit" => htmlspecialchars($homeDAO->getNbInscrit()),
            "nbStoryCreate" => htmlspecialchars($homeDAO->getNbStory())
        ]);
    }

}

?>
