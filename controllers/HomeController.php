<?php 

require(PATH_MODELS.'StoryDAO.php');
require(PATH_MODELS.'UserDAO.php');
use Models\StoryDAO;
use Models\UserDAO;

/**
 * HomeController is the controller of the home page.
 * 
 * @author  Jonathan Montmain   <jonathan.montmain@etu.univ-lyon1.fr>
 * @author  Rudy Boullier       <rudy.boullier@etu.univ-lyon1.fr>
 */
class HomeController {

    /**
     * Computes the "home" request into a response.
     */
    public static function home() {
        $userDAO = new UserDAO(strtolower($_ENV["APP_ENV"]) == "debug");
        $storyDAO = new StoryDAO(strtolower($_ENV["APP_ENV"]) == "debug");

        $view = new \Templates\View("home.twig");   
        if (isset($_SESSION['UserName'])) {
            $user = $userDAO->getUser(htmlspecialchars($_SESSION['UserName']), null);
            
            if (htmlspecialchars($user['UserAvatar']) == NULL) {
                $user['UserAvatar'] = '/assets/images/userDefaultIcon.png';
            }

            $view->render([
                "userName" => htmlspecialchars($user['UserName']),
                "userAvatar" => htmlspecialchars($user['UserAvatar']),
                "nbRegistered" => htmlspecialchars($userDAO->getNbRegistered()),
                "nbStoryCreate" => htmlspecialchars($storyDAO->getNbStory())
            ]);
        } else {
            $view->render([
                "nbRegistered" => htmlspecialchars($userDAO->getNbRegistered()),
                "nbStoryCreate" => htmlspecialchars($storyDAO->getNbStory())
            ]);
        }
    }

}

?>
