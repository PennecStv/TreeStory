<?php 

require_once(PATH_MODELS.'UserDAO.php');
require_once(PATH_MODELS.'StoryNodeDAO.php');
require_once(PATH_MODELS.'StoryNodeReadingStatisticsDAO.php');
use Models\UserDAO;
use Models\StoryNodeDAO;
use Models\StoryNodeReadingStatisticsDAO;

/**
 * AccountController is the controller of the account page.
 * 
 * @author  Rudy Boullier   <rudy.boullier@etu.univ-lyon1.fr>
 */
class AccountController {

    /**
     * Computes the "account" request into a response.
     */
    public static function account() {
        $view = new \Templates\View("account.twig");

        if (isset($_SESSION['UserName'])) {

            $userDAO = new UserDAO(strtolower($_ENV["APP_ENV"]) == "debug");
            $user = $userDAO->getUser(htmlspecialchars($_SESSION['UserName']), null);

            $snrsDAO = new StoryNodeReadingStatisticsDAO(strtolower($_ENV["APP_ENV"]) == "debug");
            $snrs = $snrsDAO->getStatisticsFavorite(htmlspecialchars($_SESSION['UserName']));

            $storyNodeDAO = new StoryNodeDAO(strtolower($_ENV["APP_ENV"]) == "debug");
            $stories = $storyNodeDAO->getUserStory(htmlspecialchars($_SESSION['UserName']));

            if (htmlspecialchars($user['UserAvatar']) == NULL) {
                $user['UserAvatar'] = '/assets/images/userDefaultIcon.png';
            }

            foreach ($stories as $key => $story) {
                    if ($story['StoryCover'] == NULL) {
                        $stories[$key]['StoryCover'] = '/assets/images/baseStoryCover.webp';
                    }

            }

            $view->render([
                "userAvatar" => htmlspecialchars($user['UserAvatar']),
                "userName" => htmlspecialchars($user['UserName']),
                "biographie" => htmlspecialchars($user['UserBiography']),
                "like" => htmlspecialchars($snrs),
                "stories" => $stories,
            ]);
            
        } else {
            header('Location: /login');
        }

    }
}

?>
