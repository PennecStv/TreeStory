<?php 

/**
 * HomeController is an example for the Routing module.
 * It's made to be modified with a real use-case.
 * 
 * @todo        Should be given a real use-case
 * @author      Jonathan Montmain <jonathan.montmain@etu.univ-lyon1.fr>
 */
class HomeController {

    /**
     * Computes the "home" request into a response.
     */
    public static function home() {
        self::greet([
            "name" => "World"
        ]);
    }

    /**
     * Computes the "greet" request into a response.
     * 
     * @param   array   $params Parameters of the request
     */
    public static function greet(array $params) {
        $view = new \Templates\View("home.twig");
        $view->render([
            "name" => htmlspecialchars($params["name"])
        ]);
    }

}

?>
