<?php

require_once(PATH_MODELS.'StoryDAO.php');
require_once(PATH_MODELS.'StoryNodeDAO.php');
require_once(PATH_MODELS.'StoryNodeReadingStatisticsDAO.php');

use Models\StoryDAO;
use Models\StoryNodeDAO;
use Models\StoryNodeReadingStatisticsDAO;
use Routing\Router;
use Dompdf\Dompdf;
use Dompdf\Options;

/**
 * StoryController class is the controller for the story pages.
 * 
 * @author  Jonathan Montmain   <jonathan.montmain@etu.univ-lyon1.fr>
 * @author  Idrissa Sall        <idrissa.sall@etu.univ-lyon1.fr>
 * @author  Rudy Boullier       <rudy.boullier@etu.univ-lyon1.fr>
 */
class StoryController {

    public static function get_creation_form() {
        if (empty($_SESSION['UserName'])) {
            header('Location: /');
            return;
        }

        $storyDao = new StoryDAO(strtolower($_ENV["APP_ENV"]) == "debug");
        $tags = $storyDao->get_tags();

        if ($tags == false) {
            Router::getInstance()->throwError("500", "Unknown database error while trying to access tags");
            return;
        }

        $view = new \Templates\View("story_creation.twig");
        $view->render([
            'tags' => $tags,
        ]);
    }

    public static function create() {
        if (!empty($_SESSION['UserName'])) {
            if (!empty($_POST['title']) && !empty($_POST['node-title']) && !empty($_POST['text']) && !empty($_POST['tags'])) {
                $anonymous = !empty($_POST['anonymous']) && $_POST['anonymous'] == 'on';
                $storyDao = new StoryDAO(strtolower($_ENV["APP_ENV"]) == "debug");
                $cover = null;
                if (!empty($_FILES["cover"])) {
                    $check = getimagesize($_FILES["cover"]["tmp_name"]);
                    if ($check != false) {
                        $cover = uniqid() . "-" . basename($_FILES["cover"]["name"]);
                        if (!move_uploaded_file($_FILES["cover"]["tmp_name"], PATH_UPLOADS . "covers/" . $cover)) $cover = null;
                    }
                }
                $story = $storyDao->create($_POST['title'], $cover);
                if ($story != false) {
                    $storyNodeDao = new StoryNodeDAO(strtolower($_ENV["APP_ENV"]) == "debug");
                    if ($storyNodeDao->create($story['StoryId'], $_POST['node-title'], $_SESSION['UserName'], $_POST['text'], $anonymous) != false) {
                        $id = $storyNodeDao->getLastNodeId();
                        if ($id == false) {
                            Router::getInstance()->throwError("500", "Unknown database error while trying to create a story");
                            return;
                        }
                        header('Location: /story/chapter/' . $id . '/read');
                    } else {
                        Router::getInstance()->throwError("500", "Unknown database error while trying to create a story");
                        return;
                    }
                } else {
                    Router::getInstance()->throwError("500", "Unknown database error while trying to create a story");
                    return;
                }
            }
        }

        self::get_creation_form();
    }

    public static function create_node($params) {
        if (empty($_SESSION['UserName'])) {
            header('Location: /');
            return;
        }

        if (!preg_match("/^[0-9]+$/", $params['id'])) {
            Router::getInstance()->throwError("500", "Bad story node id requested");
            return;
        }

        $storyNodeDao = new StoryNodeDAO(strtolower($_ENV["APP_ENV"]) == "debug");

        $beforeStoryNode = $storyNodeDao->get(intval($params['id']));

        if ($beforeStoryNode == false) {
            Router::getInstance()->throwError("500", "Unknown database error while trying to access story next nodes from id: " . $params['id']);
            return;
        }

        if (!empty($_POST['title']) && !empty($_POST['text'])) {
            $anonymous = !empty($_POST['anonymous']) && $_POST['anonymous'] == 'on';
            if ($storyNodeDao->create($beforeStoryNode['StoryNodeSource'], $_POST['title'], $_SESSION['UserName'], $_POST['text'], $anonymous, $beforeStoryNode['StoryNodeId'])) {
                header('Location: /story/chapter/' . $storyNodeDao->getLastNodeId() . '/read');
                return;
            } else {
                Router::getInstance()->throwError(500, "Unknown database error while trying to create a story");
            }
        } else {
            self::get_node_creation_form($params['id']);
        }
    }

    public static function read($params) {

        $boutonLike = "J'aime";
        $label_aria = "unfavorite";
        $storyNodeDao = new StoryNodeDAO(strtolower($_ENV["APP_ENV"]) == "debug");
        $storyNodeReadingStatisticsDao = new storyNodeReadingStatisticsDao(strtolower($_ENV["APP_ENV"]) == "debug");

        if (!preg_match("/^[0-9]+$/", $params['id'])) {
            Router::getInstance()->throwError("500", "Bad story node id requested");
            return;
        }

        $storyNode = $storyNodeDao->get(intval($params['id']));

        if ($storyNode == false) {
            Router::getInstance()->throwError("500", "Unknown database error while trying to access story id: " . $params['id']);
            return;
        }

        $isAuthor = false;
        if (!empty($_SESSION['UserName']) && $storyNode['StoryNodeAuthor'] == $_SESSION['UserName']) {
            $isAuthor = true;
        }

        $author = null;
        if ($storyNode['StoryNodeAnonymous'] == 0) {
            $author = $storyNode['StoryNodeAuthor'];
        }

        $nextStoryNodes = $storyNodeDao->getNext(intval($params['id']));

        if ($storyNode == false) {
            Router::getInstance()->throwError("500", "Unknown database error while trying to access story next nodes from id: " . $params['id']);
            return;
        }

        $nextNodes = [];

        foreach ($nextStoryNodes as $nextStoryNode) {
            $nextNodes[] = [
                'title' => substr($nextStoryNode['StoryNodeTitle'], 0, 20),
                'id' => $nextStoryNode['StoryNodeId'],
            ];
        }

        if (!(empty($_SESSION['UserName']))){
            $isLiked = $storyNodeDao->getLikeChapter($_SESSION['UserName'], intval($params['id']));
            if($isLiked){
                $boutonLike = "Je n'aime plus";
            }
        }
        

        $comments = $storyNodeDao->getComments($storyNode['StoryNodeId']);

        foreach($comments as $key => $comment){
            if($comment['UserAvatar'] == null){
                $comments[$key]['UserAvatar'] = '/assets/images/userDefaultIcon.png';
            }else{
                $comments[$key]['UserAvatar'] = '/assets/uploads/'.$comments[$key]['UserAvatar'];
            }
        }

        if (!(empty($_SESSION['UserName']))){
            $favorites = $storyNodeReadingStatisticsDao->estFavorite($_SESSION['UserName'], intval($params['id']));
            if($favorites){
                $label_aria = "favorite";
            }
        }

        $view = new \Templates\View("story_read.twig");
        $view->render([
            'title' => $storyNode['StoryNodeTitle'],
            'text' => $storyNode['StoryNodeText'],
            'story_id' => $storyNode['StoryNodeSource'],
            'story_node_id' => $storyNode['StoryNodeId'],
            'next_story_nodes' => $nextNodes,
            'is_author' => $isAuthor,
            'author' => $author,
            'previous' => $storyNode['StoryNodeRoot'],
            'boutonLike' => $boutonLike,
            'comments' => $comments,
            'label_aria' => $label_aria,
        ]);
    }

    public static function get_node_edition_form($params) {
        if (empty($_SESSION['UserName'])) {
            header('Location: /');
            return;
        }

        if (!preg_match("/^[0-9]+$/", $params['id'])) {
            Router::getInstance()->throwError("500", "Bad story node id requested");
            return;
        }

        $storyNodeDao = new StoryNodeDAO(strtolower($_ENV["APP_ENV"]) == "debug");

        $storyNode = $storyNodeDao->get(intval($params['id']));

        if ($storyNode == false) {
            Router::getInstance()->throwError("500", "Unknown database error while trying to access story next nodes from id: " . $params['id']);
            return;
        }

        if ($storyNode['StoryNodeAuthor'] != $_SESSION['UserName']) {
            header('Location: /');
            return;
        }

        $view = new \Templates\View("story_node_edition.twig");
        $view->render([
            'title' => $storyNode['StoryNodeTitle'],
            'content' => $storyNode['StoryNodeText'],
            'anonymous' => $storyNode['StoryNodeAnonymous'],
        ]);
    }

    public static function get($params) {
        $storyDao = new StoryDAO(strtolower($_ENV["APP_ENV"]) == "debug");

        if (!preg_match("/^[0-9]+$/", $params['id'])) {
            Router::getInstance()->throwError("500", "Bad story id requested");
            return;
        }

        $story = $storyDao->get(intval($params['id']));

        if ($story == false) {
            Router::getInstance()->throwError("500", "Unknown database error while trying to access story id: " . $params['id']);
            return;
        }

        $storyNodeDao = new StoryNodeDAO(strtolower($_ENV["APP_ENV"]) == "debug");
        $firstNode = $storyNodeDao->getFirstInStory(intval($params['id']));

        if ($firstNode == false) {
            Router::getInstance()->throwError("500", "Unknown database error while trying to access first node in story id: " . $params['id']);
            return;
        }

        $view = new \Templates\View("story_show.twig");
        $view->render([
            'title' => $story['StoryTitle'],
            'cover' => $story['StoryCover'],
            'first' => $firstNode,
        ]);
    }

    public function edit($params) {
        if (empty($_SESSION['UserName'])) {
            header('Location: /');
            return;
        }

        if (!preg_match("/^[0-9]+$/", $params['id'])) {
            Router::getInstance()->throwError("500", "Bad story node id requested");
            return;
        }

        $storyNodeDao = new StoryNodeDAO(strtolower($_ENV["APP_ENV"]) == "debug");

        $storyNode = $storyNodeDao->get(intval($params['id']));

        if ($storyNode == false) {
            Router::getInstance()->throwError("500", "Unknown database error while trying to access story next nodes from id: " . $params['id']);
            return;
        }

        if ($storyNode['StoryNodeAuthor'] != $_SESSION['UserName']) {
            header('Location: /');
            return;
        }

        $anonymous = !empty($_POST['anonymous']) && $_POST['anonymous'] == 'on';

        if (!empty($_POST['title']) && !empty($_POST['text'])) {
            $storyNodeDao->edit($storyNode['StoryNodeId'], $_POST['title'], $_POST['text'], $anonymous);
            header('Location: /story/chapter/' . $storyNode['StoryNodeId'] . '/read');
            return;
        } else {
            header('Location: /story/chapter/' . $storyNode['StoryNodeId'] . '/edit');
            return;
        }
    }

    public static function get_node_creation_form($params) {
        if (empty($_SESSION['UserName'])) {
            header('Location: /');
            return;
        }

        if (!preg_match("/^[0-9]+$/", $params['id'])) {
            Router::getInstance()->throwError("500", "Bad story node id requested");
            return;
        }

        $storyNodeDao = new StoryNodeDAO(strtolower($_ENV["APP_ENV"]) == "debug");

        $storyNode = $storyNodeDao->get(intval($params['id']));

        if ($storyNode == false) {
            Router::getInstance()->throwError("500", "Unknown database error while trying to access story next nodes from id: " . $params['id']);
            return;
        }

        $view = new \Templates\View("story_node_creation.twig");
        $view->render([]);
    }


    /**
     * this function is used to delete a chapter.
     * @param $params
     */
    public static function delete_chapter($params){
        $storyNodeDAO = new StoryNodeDAO(strtolower($_ENV["APP_ENV"]) == "debug");
        $storyNode = $storyNodeDAO->get(intval($params['id']));
        if($storyNode['StoryNodeAuthor'] == $_SESSION['UserName']){
            $storyNodeDAO->deleteStory(intval($params['id']));
            //header('Location: /story/chapter/' . $storyNode['StoryNodeRoot'] . '/read');
            return;
        }

    }


    /**
     * this function is used to report a chapter.
     * @param $params
     */
    public static function report_chapter($params){
        
        $messageErreur = "";
        $classeMessage = "";

        if(isset($_POST['signaler'])){
            
            extract($_POST);

            $storyNodeDao = new StoryNodeDAO(strtolower($_ENV["APP_ENV"]) == "debug");
            $isConform = $storyNodeDao->getCheckBannedWords($text_signalement);
            if($isConform){
                $storyNodeDao->reportStoryNode($report_type, intval($params['id']), $_SESSION['UserName'], $text_signalement);
                $messageErreur = "Merci, votre signalement a bien été pris en compte.";
                $classeMessage = "succes";
            }
            else{
                $messageErreur = "Le texte contient des mots interdits. Votre signalement n'a pas été pris en compte.";
                $classeMessage = "erreur";
            }
        }

        $view = new \Templates\View("report.twig");
        $view->render([
            'id' => intval($params['id']),
            'messageErreur' => $messageErreur,
            'classeMessage' => $classeMessage
        ]);
    }

    /**
     * this function is used to like a chapter.
     * @param $params
     */
    public static function like_chapter($params){
        $storyNodeDao = new StoryNodeDAO(strtolower($_ENV["APP_ENV"]) == "debug");
        $storyNodeDao->setStoryNodeLikes(intval($params['id']), "like");
        $storyNodeDao->addLikeChapter($_SESSION["UserName"],intval($params['id']));
        
    }


    /**
     * this function is used to dislike a chapter.
     * @param $params
     */
    public static function dislike_chapter($params){
        $storyNodeDao = new StoryNodeDAO(strtolower($_ENV["APP_ENV"]) == "debug");
        $storyNodeDao->setStoryNodeLikes(intval($params['id']), "dislike");
        $storyNodeDao->removeLikeChapter($_SESSION['UserName'],intval($params['id']));
    }

    
    /**
     * this function is used to comment a chapter.
     * @param $params
     */
    public static function comment_chapter($params){
        $storyNodeDao = new StoryNodeDAO(strtolower($_ENV["APP_ENV"]) == "debug");
        $storyNodeDao->addComments($_SESSION['UserName'],intval($params['id']), $_REQUEST['comment']);   
    }


    /**
     * this function is used to generate a pdf.
     * @param $params
     */
    public static function generate_pdf($params) {

        $storyNodeDao = new StoryNodeDAO(strtolower($_ENV["APP_ENV"]) == "debug");
        $storyNode = $storyNodeDao->get(intval($params['id']));

        $author = "Anonymous";
        if ($storyNode['StoryNodeAnonymous'] == 0) {
            $author = $storyNode['StoryNodeAuthor'];
        }

        ob_start();
        header("Content-Disposition: attachment; filename=\"" . $storyNode["StoryNodeTitle"] . ".pdf\"");
        $view = new \Templates\View("/base/pdf.twig");
        $view->render([
            'storyTitle' => $storyNode['StoryNodeTitle'],
            'storyAuthor' => $author,
            'storyText' => $storyNode['StoryNodeText']
        ]);
        $html = ob_get_contents();
        ob_end_clean();

        $options = new Options();
        $options->set('defaultFont', 'sans-serif');

        $fileName = $storyNode['StoryNodeTitle'].".pdf";

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream($fileName);
    }


    /**
     * this function is used to favorite a chapter.
     * @param $params
     */
    public static function favorite_chapter($params) {
        $storyNodeDao = new StoryNodeDAO(strtolower($_ENV["APP_ENV"]) == "debug");
        $storyNode = $storyNodeDao->get(intval($params['id']));

        $storyNodeReadingStatisticsDao = new storyNodeReadingStatisticsDao(strtolower($_ENV["APP_ENV"]) == "debug");
        $storyNodeReadingStatisticsDao->addFavorite(intval($params['id']), $_SESSION['UserName']);
    }


    /**
     * this function is used to unfavorite a chapter.
     * @param $params
     */
    public static function unfavorite_chapter($params) {
        $storyNodeDao = new StoryNodeDAO(strtolower($_ENV["APP_ENV"]) == "debug");
        $storyNode = $storyNodeDao->get(intval($params['id']));

        $storyNodeReadingStatisticsDao = new storyNodeReadingStatisticsDao(strtolower($_ENV["APP_ENV"]) == "debug");
        $storyNodeReadingStatisticsDao->removeFavorite(intval($params['id']), $_SESSION['UserName']);
    }

}

?>