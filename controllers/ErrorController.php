<?php 

/**
 * ErrorController is a controller made to handle errors cases.
 * 
 * @author      Jonathan Montmain <jonathan.montmain@etu.univ-lyon1.fr>
 */
class ErrorController {

    /**
     * Handles the "404 Not Found" error into a response.
     */
    public static function notFound() {
        http_response_code(404);
        $view = new \Templates\View("errors/404.twig");
        $view->render([]);
    }

}

?>
