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
 * @author  Idrissa Sall    <idrissa.sall@etu.univ-lyon1.fr>
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

            
            $follower = count($userDAO->getFollowers('FollowingUserId',$user['UserName']));
            $following = count($userDAO->getFollowers('UserId',$user['UserName']));

            $view->render([
                "userAvatar" => htmlspecialchars($user['UserAvatar']),
                "userName" => htmlspecialchars($user['UserName']),
                "biographie" => htmlspecialchars($user['UserBiography']),
                "like" => htmlspecialchars($snrs),
                "stories" => $stories,

                "userNameConnected" => ($_SESSION['UserName']),
                "follower" => $follower,
                "following" =>$following,
            ]);
            
        } else {
            header('Location: /login');
        }

    }


    /**
     * Displays a user's profile.
     */
    public static function displayAccount($params) {        
        $view = new \Templates\View("account.twig");
        $buttonName = "Suivre";

        if (isset($_SESSION['UserName'])) {

            $userDAO = new UserDAO(strtolower($_ENV["APP_ENV"]) == "debug");
            $user = $userDAO->getUser(htmlspecialchars($params['userId']), null);

            $snrsDAO = new StoryNodeReadingStatisticsDAO(strtolower($_ENV["APP_ENV"]) == "debug");
            $snrs = $snrsDAO->getStatisticsFavorite(htmlspecialchars($params['userId']));

            $storyNodeDAO = new StoryNodeDAO(strtolower($_ENV["APP_ENV"]) == "debug");
            $stories = $storyNodeDAO->getUserStory(htmlspecialchars($params['userId']));

            foreach ($stories as $key => $story) {
                if ($story['StoryCover'] == NULL) {
                    $stories[$key]['StoryCover'] = '/assets/images/baseStoryCover.webp';
                }

            }
            
            if(!empty($user['UserName'])){

                if (htmlspecialchars($user['UserAvatar']) == NULL) {
                    $user['UserAvatar'] = '/assets/images/userDefaultIcon.png';
                }else{
                    $user['UserAvatar'] = '/assets/images/'.$user['UserAvatar'];
                }

                $followRelation = $userDAO->getFollows($_SESSION['UserName'], $user['UserName']);
                if(!empty($followRelation)){
                    $buttonName = "Ne plus suivre";
                }

                $follower = count($userDAO->getFollowers('FollowingUserId',$user['UserName']));
                $following = count($userDAO->getFollowers('UserId',$user['UserName']));

                $view->render([
                    "userAvatar" => $user['UserAvatar'],
                    "userName" => htmlspecialchars($user['UserName']),
                    "biographie" => htmlspecialchars($user['UserBiography']),
                    "like" => htmlspecialchars($snrs),
                    "stories" => $stories,

                    "userNameConnected" => $_SESSION['UserName'],
                    "buttonName" => $buttonName,
                    "follower" => $follower,
                    "following" =>$following,

                ]);
            }
            
        } else {
            header('Location: /login');
        }
    }


    /**
     * Follows a user. 
     */
    public static function follow($params){
        $userDAO = new UserDAO(strtolower($_ENV["APP_ENV"]) == "debug");
        $user = $userDAO->getUser(htmlspecialchars($params['id']), null);
        if(!empty($user)){
            $userDAO->insertFollowRelation($_SESSION['UserName'], $user['UserName']);
        }
    }


    /**
     * Unfollows a user. 
     */
    public static function unfollow($params){
        $userDAO = new UserDAO(strtolower($_ENV["APP_ENV"]) == "debug");
        $user = $userDAO->getUser($params['id'], null);
        if(!empty($user)){
            $userDAO->deleteFollowRelation($_SESSION['UserName'], $user['UserName']);
        }
    }

    public static function download($res){
        $userDAO = new UserDAO(strtolower($_ENV["APP_ENV"]) == "debug");
        $user = $userDAO->getUser(htmlspecialchars($_SESSION['UserName']), null);
        $res = $user;
        // Ouvre le fichier pour obtenir un pointeur de fichier
        $handle = fopen('data.txt', 'w');

        // Ecrit les données dans le fichier
        fwrite($handle, "test test");

        // Ferme le pointeur de fichier
        fclose($handle);
    }
}

?>
