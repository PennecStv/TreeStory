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

        $userDAO      = new UserDAO(strtolower($_ENV["APP_ENV"]) == "debug");
        $storyDAO     = new StoryDAO(strtolower($_ENV["APP_ENV"]) == "debug");
        $storyNodeDAO = new StoryNodeDAO(strtolower($_ENV["APP_ENV"]) == "debug");

        $storyResult   = [];
        $chapterResult = [];
        $userResult    = [];

        if (isset($_POST["search"])) {
            $search = $_POST["search"];
        } else {
            $search = null;
        }

        if (isset($_POST["filter"]) || isset($_POST["sort"])){
            $filter = $_POST["filter"];
            $sort   = $_POST["sort"];
        } else {
            $filter = null;
            $sort   = null;
            
        }

        if (isset($_SESSION['UserName'])) {
            $user = $userDAO->getUser(htmlspecialchars($_SESSION['UserName']), null);
            
            if (htmlspecialchars($user['UserAvatar']) == NULL) {
                $user['UserAvatar'] = '/assets/images/userDefaultIcon.png';
            }
        }

        $filterList = array(
            ["all",  "Tout"],
            ["story", "Histoire"],
            ["storyNode", "Chapitre"],
            ["user", "Utilisateur"]
        );

        $sortList = array(
            ["null", " -- "],
            ["inorder",  "A - Z"],
            ["inverse", "Z - A"],
            ["like", "Les plus aimées"],
            ["view", "Les plus vues"],
            ["recent", "Les plus récentes"]
        );

        switch($sort){
            case "inorder":
                $indexSort = 1;
                break;
            
            case "inverse":
                $indexSort = 2;
                break;

            case "like":
                $indexSort = 3;
                break;

            case "view":
                $indexSort = 4;
                break;

            case "recent":
                $indexSort = 5;
                break;

            default:
                $indexSort = 0;
                break;
        }

        switch($filter){
            case "story":
                $indexFilter = 1;
                $storyResult   = $storyDAO->getStoryByResearch(htmlspecialchars($search), $sort);
                break;

            case "storyNode":
                $indexFilter = 2;
                $chapterResult = $storyNodeDAO->getStoryNodeByResearch(htmlspecialchars($search), $sort);
                break;

            case "user":
                $indexFilter = 3;
                $userResult    = $userDAO->getUsersByResearch(htmlspecialchars($search), $sort);
                break;

            default:
                $indexFilter = 0;
                $storyResult   = $storyDAO->getStoryByResearch(htmlspecialchars($search), $sort);
                $chapterResult = $storyNodeDAO->getStoryNodeByResearch(htmlspecialchars($search), $sort);
                $userResult    = $userDAO->getUsersByResearch(htmlspecialchars($search), $sort);
                break;
        }

        $temp = $sortList[$indexSort];
        unset($sortList[$indexSort]);
        array_unshift($sortList, $temp);

        $temp = $filterList[$indexFilter];
        unset($filterList[$indexFilter]);
        array_unshift($filterList, $temp);
        

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
        if (empty($user)){
            $view->render([
                "stories"  => $results,
                "chapters" => $chapterResult,
                "users"    => $userResult,
                "filters"   => $filterList,
                "sorts"    => $sortList,
                "searchText" => $search,
                "notFound" => $notFound
            ]);
        } else {
            $view->render([
                "userName" => htmlspecialchars($user['UserName']),
                "userAvatar" => htmlspecialchars($user['UserAvatar']),
                "stories"  => $results,
                "chapters" => $chapterResult,
                "users"    => $userResult,
                "filters"  => $filterList,
                "sorts"    => $sortList,
                "searchText" => $search,
                "notFound" => $notFound
            ]);
        }

        
    }
}

?>