<?php

require_once(PATH_MODELS.'StoryDAO.php');
require_once(PATH_MODELS.'StoryNodeDAO.php');

use Models\StoryDAO;
use Models\StoryNodeDAO;
use Routing\Router;


/**
 * StoryController class is the controller for the story pages.
 * 
 * @author  Jonathan Montmain   <jonathan.montmain@etu.univ-lyon1.fr>
 * @author  Idrissa Sall    <idrissa.sall@etu.univ-lyon1.fr>
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
        $storyNodeDao = new StoryNodeDAO(strtolower($_ENV["APP_ENV"]) == "debug");

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

}

?>