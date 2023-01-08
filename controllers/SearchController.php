<?php 

require_once(PATH_MODELS.'UserDAO.php');
require_once(PATH_MODELS.'StoryDAO.php');
use Models\UserDAO;
use Models\StoryDAO;

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

        $userDAO  = new UserDAO(strtolower($_ENV["APP_ENV"]) == "debug");
        $storyDAO = new StoryDAO(strtolower($_ENV["APP_ENV"]) == "debug");

        $storyResult   = $storyDAO->getStoryByResearch(htmlspecialchars($search));
        $chapterResult = $storyDAO->getStoryNodeByResearch(htmlspecialchars($search));
        $userResult    = $userDAO->getUsersByResearch(htmlspecialchars($search));

        $notFound = empty($storyResult) && empty($chapterResult) && empty($userResult);

        $results = null;

        if (!$notFound){
            $results = $storyResult;
        }

        $view = new \Templates\View("search.twig"); 
        $view->render([
            "stories"  => $results,
            "chapters" => $chapterResult,
            "users"    => $userResult,
            "notFound" => $notFound
        ]);
    }
}

?>