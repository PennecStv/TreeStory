<?php 

require_once(PATH_MODELS.'UserDAO.php');
require_once(PATH_MODELS.'StoryNodeDAO.php');
require_once(PATH_MODELS.'StoryNodeReadingStatisticsDAO.php');
use Models\UserDAO;
use Models\StoryNodeDAO;
use Models\StoryNodeReadingStatisticsDAO;
USE Models\StoryTagDAO;

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

            $storyTagDAO = new StoryTagDAO(strtolower($_ENV["APP_ENV"])  == "debug");

            if (htmlspecialchars($user['UserAvatar']) == NULL) {
                $user['UserAvatar'] = '/assets/images/userDefaultIcon.png';
            }else{
                $user['UserAvatar'] = '/assets/uploads/'.$user['UserAvatar'];
            }

            foreach ($stories as $key => $story) {
                if ($story['StoryCover'] == NULL) {
                    $stories[$key]['StoryCover'] = '/assets/images/baseStoryCover.webp';
                }else{
                    $stories[$key]['StoryCover'] = '/assets/uploads/covers/'.$stories[$key]['StoryCover'];
                }

                $storyTagResult = $storyTagDAO->getStoryTagByStoryId($stories[$key]['StoryNodeSource']);
                $stories[$key]['StoryTag'] = $storyTagResult; 
            }

            $subscribers = $userDAO->getUsersSubscribers('UserId',$user['UserName']);
            $subscriptions = $userDAO->getUsersSubscriptions('FollowingUserId',$user['UserName']);

            foreach ($subscribers as $key => $subscriber) {
                if ($subscriber['UserAvatar'] == NULL) {
                    $subscribers[$key]['UserAvatar'] = '/assets/images/userDefaultIcon.png';
                }else{
                    $subscribers[$key]['UserAvatar'] = '/assets/uploads/'.$subscribers[$key]['UserAvatar'];
                }
            }

            foreach ($subscriptions as $key => $subscription) {
                if ($subscription['UserAvatar'] == NULL) {
                    $subscriptions[$key]['UserAvatar'] = '/assets/images/userDefaultIcon.png';
                }else{
                    $subscriptions[$key]['UserAvatar'] = '/assets/uploads/'.$subscriptions[$key]['UserAvatar'];
                }
            }

            $follower = count($userDAO->getFollowers('FollowingUserId',$user['UserName']));
            $following = count($userDAO->getFollowers('UserId',$user['UserName']));

            $favoriteHistories = $snrsDAO->getFavorites($_SESSION['UserName']);  
            foreach ($favoriteHistories as $key => $favoriteHistory) {
                if ($favoriteHistory['StoryCover'] == NULL) {
                    $favoriteHistories[$key]['StoryCover'] = '/assets/images/baseStoryCover.webp';
                }else{
                    $favoriteHistories[$key]['StoryCover'] = '/assets/uploads/covers/'.$favoriteHistories[$key]['StoryCover'];
                }
            }     


            $view->render([
                "userAccountAvatar" => $user['UserAvatar'],
                "userAccountName" => htmlspecialchars($user['UserName']),
                "biographie" => htmlspecialchars($user['UserBiography']),
                "like" => htmlspecialchars($snrs),
                "stories" => $stories,
                "subscribers" => $subscribers,
                "subscriptions" => $subscriptions,
                "favoriteHistories" => $favoriteHistories,

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

            $storyTagDAO = new StoryTagDAO(strtolower($_ENV["APP_ENV"])  == "debug");
            

            foreach ($stories as $key => $story) {
                if ($story['StoryCover'] == NULL) {
                    $stories[$key]['StoryCover'] = '/assets/images/baseStoryCover.webp';
                }else{
                    $stories[$key]['StoryCover'] = '/assets/uploads/covers/'.$stories[$key]['StoryCover'];
                }

                $storyTagResult = $storyTagDAO->getStoryTagByStoryId($story['StoryNodeSource']);
                $story['StoryTag'] = $storyTagResult; 
            }
            
            if(!empty($user['UserName'])){

                if (htmlspecialchars($user['UserAvatar']) == NULL) {
                    $user['UserAvatar'] = '/assets/images/userDefaultIcon.png';
                }else{
                    $user['UserAvatar'] = '/assets/uploads/'.$user['UserAvatar'];
                }

                $followRelation = $userDAO->getFollows($_SESSION['UserName'], $user['UserName']);
                if(!empty($followRelation)){
                    $buttonName = "Ne plus suivre";
                }

                $subscribers = $userDAO->getUsersSubscribers('UserId',$user['UserName']);
                $subscriptions = $userDAO->getUsersSubscriptions('FollowingUserId',$user['UserName']);

                foreach ($subscribers as $key => $subscriber) {
                    if ($subscriber['UserAvatar'] == NULL) {
                        $subscribers[$key]['UserAvatar'] = '/assets/images/userDefaultIcon.png';
                    }else{
                        $subscribers[$key]['UserAvatar'] = '/assets/uploads/'.$subscribers[$key]['UserAvatar'];
                    }
                }
                
                foreach ($subscriptions as $key => $subscription) {
                    if ($subscription['UserAvatar'] == NULL) {
                        $subscriptions[$key]['UserAvatar'] = '/assets/images/userDefaultIcon.png';
                    }else{
                        $subscriptions[$key]['UserAvatar'] = '/assets/uploads/'.$subscriptions[$key]['UserAvatar'];
                    }
                }

                $follower = count($userDAO->getFollowers('FollowingUserId',$user['UserName']));
                $following = count($userDAO->getFollowers('UserId',$user['UserName']));

                $favoriteHistories = $snrsDAO->getFavorites($user['UserName']);  
                foreach ($favoriteHistories as $key => $favoriteHistory) {
                    if ($favoriteHistory['StoryCover'] == NULL) {
                        $favoriteHistories[$key]['StoryCover'] = '/assets/images/baseStoryCover.webp';
                    }else{
                        $favoriteHistories[$key]['StoryCover'] = '/assets/uploads/covers/'.$favoriteHistories[$key]['StoryCover'];
                    }
                } 

                $view->render([
                    "userAccountAvatar" => $user['UserAvatar'],
                    "userAccountName" => htmlspecialchars($user['UserName']),
                    "biographie" => htmlspecialchars($user['UserBiography']),
                    "like" => htmlspecialchars($snrs),
                    "stories" => $stories,
                    "subscribers" => $subscribers,
                    "subscriptions" => $subscriptions,
                    "favoriteHistories" => $favoriteHistories,

                    "userNameConnected" => $_SESSION['UserName'],
                    "buttonName" => $buttonName,
                    "follower" => $follower,
                    "following" =>$following,

                ]);
                
            }else{
                Router::getInstance()->throwError("404", "Page not found");
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

}

?>
