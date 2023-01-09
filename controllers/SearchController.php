<?php 

require_once(PATH_MODELS.'UserDAO.php');
require_once(PATH_MODELS.'StoryDAO.php');
use Models\UserDAO;
use Models\StoryDAO;
Use Models\StoryNodeDAO;

/**
 * SearchController is the controller of the research page.
 * 
 * @author  Steve Pennec  <steve.pennec@etu.univ-lyon1.fr>
 */
class SearchController {

    /**
     * 
     */
    public static function search(){

        if (isset($_POST["search"])){
            extract($_POST);
        } else {
            $search = null;
        }

        $userDAO      = new UserDAO(strtolower($_ENV["APP_ENV"]) == "debug");
        $storyDAO     = new StoryDAO(strtolower($_ENV["APP_ENV"]) == "debug");
        $storyNodeDAO = new StoryNodeDAO(strtolower($_ENV["APP_ENV"]) == "debug");

        if (isset($_SESSION['UserName'])) {
            $user = $userDAO->getUser(htmlspecialchars($_SESSION['UserName']), null);
            
            if (htmlspecialchars($user['UserAvatar']) == NULL) {
                $user['UserAvatar'] = '/assets/images/userDefaultIcon.png';
            }
        }

        
        $storyResult   = $storyDAO->getStoryByResearch(htmlspecialchars($search));
        $chapterResult = $storyNodeDAO->getStoryNodeByResearch(htmlspecialchars($search));
        $userResult    = $userDAO->getUsersByResearch(htmlspecialchars($search));

        $notFound = empty($storyResult) && empty($chapterResult) && empty($userResult);

        $results = null;

        foreach ($storyResult as $key => $story) {
            if ($story['StoryCover'] == NULL) {
                $storyResult[$key]['StoryCover'] = '/assets/images/baseStoryCover.webp';
            }
        }

        foreach ($chapterResult as $key => $chapter) {
            if ($chapter['StoryCover'] == NULL) {
                $chapterResult[$key]['StoryCover'] = '/assets/images/baseStoryCover.webp';
            }
        }

        foreach ($userResult as $key => $story) {
            if ($story['UserAvatar'] == NULL) {
                $userResult[$key]['UserAvatar'] = '/assets/images/userDefaultIcon.png';
            }
        }

        if (!$notFound){
            $results = $storyResult;
        }

        $view = new \Templates\View("search.twig"); 
        $view->render([
            "userName" => htmlspecialchars($user['UserName']),
            "userAvatar" => htmlspecialchars($user['UserAvatar']),
            "stories"  => $results,
            "chapters" => $chapterResult,
            "users"    => $userResult,
            "notFound" => $notFound
        ]);
    }
}

?>