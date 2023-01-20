<?php

namespace Models;

require_once(PATH_UTILS_DATABASE.'DAO.php');
use Database\DAO;

/**
 * It extends from class DAO. It defines methods for 
 * retrieving specific data from the database.
 * 
 * @author Rudy Boullier    <rudy.boullier@etu.univ-lyon1.fr>
 * @author  Steve Pennec    <steve.pennec@etu.univ-lyon1.fr>
 */
class StoryNodeDAO extends DAO {

    /**
     * Get the stories of a specific user
     * 
     * @param   String  $userName   Username of the user
     * 
     * @return  false|PDOStatement        query results
     */
    public function getUserStory(String $userName) { 
        $req = "SELECT * FROM StoryNode sn, Story s WHERE sn.StoryNodeSource = s.StoryId AND sn.StoryNodeAuthor = ?";
        return $this->queryAll($req, [$userName]);
    }
    
    public function create(int $storyId, string $title, string $username, string $text, bool $anonymous, int $antecedent = null) {
        return $this->insert("INSERT INTO StoryNode (StoryNodeTitle, StoryNodeAuthor, StoryNodeSource, StoryNodeText, StoryNodeAnonymous, StoryNodeRoot, StoryNodePublishedAt) VALUES (?, ?, ?, ?, ?, ?, ?);", [$title, $username, $storyId, $text, $anonymous ? 1 : 0, $antecedent, date("Y-m-d H:i:s", time())]);
    }

    public function get(int $storyNodeId) {
        return $this->queryRow("SELECT * FROM StoryNode WHERE StoryNodeId = ?", [$storyNodeId]);
    }

    public function getNext(int $storyNodeId) {
        return $this->queryAll("SELECT * FROM StoryNode WHERE StoryNodeRoot = ?", [$storyNodeId]);
    }

    public function getFirstInStory(int $storyId) {
        return $this->queryRow("SELECT * FROM StoryNode WHERE StoryNodeSource = ? AND StoryNodeRoot IS NULL", [$storyId]);
    }

    public function getLastNodeId() {
        $res = $this->queryRow("SELECT max(StoryNodeId) as id FROM StoryNode");
        if ($res != false) return $res['id'];
        return false;
    }

    public function edit(int $storyId, string $title, string $text, bool $anonymous) {
        return $this->queryRow("UPDATE StoryNode SET StoryNodeTitle = ?, StoryNodeText = ?, StoryNodeAnonymous = ? WHERE StoryNodeId = ?", [$title, $text, $anonymous ? 1 : 0, $storyId]);
    }


    /**
     * Delete a storyNode
     * @param  int     $storyId    Id of the storyNode
     */
    public function deleteStory(int $storyId) {
        $this->queryRow("DELETE FROM StoryNode WHERE StoryNodeId = ?", [$storyId]);
    }


    /**
     * Insert a report in the database
     * @param  int     $reportId              Id of the report
     * @param  string  $reportType            Type of the report
     * @param  string  $reportUserSource      Username of the user who reported
     * @param  string  $reportDescription     Description of the report
     */
    public function reportStoryNode(string $reportType, int $storyIdTarget ,string $reportUserSource, string $reportDescription) {
        return $this->insert("INSERT INTO Report (ReportType, ReportStoryTarget, ReportUserSource, ReportDescription, ReportCreatedAt) VALUES (?, ?, ?, ?, ?);", [$reportType,$storyIdTarget, $reportUserSource, $reportDescription ,date("Y-m-d H:i:s", time())]);
    }

    /**
     * Check if the text contains banned words
     * @param  string $text Text to check
     * 
     * @return bool   True if the text is valid, false otherwise
     */
    public function getCheckBannedWords(string $text) {
        $banned_words = array("insulte", "femmelette", "sexiste", "invective", "ordurier");
        foreach ($banned_words as $word) {
            if (stripos($text, $word) !== false) {
                return false;
            }
        }
        return true;
    }


    /**
     * Set the likes of a storyNode
     * 
     * @param   String  $userName   Username of the user
     * 
     * @return  false|PDOStatement        query results
     */
    public function setStoryNodeLikes(int $storyNodeId, String $action) {
        if($action == "like"){
           return $this->queryRow("UPDATE StoryNode SET StoryNodeLikes = StoryNodeLikes + 1 WHERE StoryNodeId = ?", [$storyNodeId]);
        }
        else if($action == "dislike"){
           return $this->queryRow("UPDATE StoryNode SET StoryNodeLikes = StoryNodeLikes - 1 WHERE StoryNodeId = ?", [$storyNodeId]);
        }
    }


    /**
     * Get the likes of a storyNode
     * 
     * @param   String  $userName   Username of the user
     * 
     * @return  false|PDOStatement        query results
     */
    public function getLikeChapter(String $username, int $storyNodeId) {
        return $this->queryRow("SELECT * FROM UserLikeRelation WHERE UserName = ? AND StoryNodeId = ?", [$username, $storyNodeId]);
    }


    /**
     * Add a like to a storyNode
     * 
     * @param   String  $userName   Username of the user
     */
    public function addLikeChapter(String $username, int $storyNodeId) {
        $this->insert("INSERT INTO UserLikeRelation (UserName, StoryNodeId) VALUES (?, ?);", [$username, $storyNodeId]);
    }


    /**
     * Remove a like to a storyNode
     * 
     * @param   String  $userName   Username of the user
     */
    public function removeLikeChapter(String $username, int $storyNodeId) {
        $this->queryRow("DELETE FROM UserLikeRelation WHERE UserName = ? AND StoryNodeId = ?", [$username, $storyNodeId]);
    }

    /**
     * add a comment to a storyNode
     * 
     * @param   String  $userName   Username of the user
     * @param   int     $storyNodeId    Id of the storyNode
     * @param   String  $textComment   Text of the comment
     */
    public function addComments(String $username, int $storyNodeId, String $textComment) {
        $this->insert("INSERT INTO Comment (CommentAuthor, CommentTarget, CommentMessage) VALUES (?, ?, ?)", [$username, $storyNodeId, $textComment]);
    }


    /**
     * get all comments of a storyNode
     * 
     * @param   int     $storyNodeId    Id of the storyNode
     * 
     * @return  false|PDOStatement        query results
     */
    public function getComments(int $storyNodeId) {
        return $this->queryAll("SELECT CommentAuthor, UserAvatar, CommentMessage FROM Comment INNER JOIN User ON User.UserName = Comment.CommentAuthor WHERE CommentTarget = ?", [$storyNodeId]);
    }


    /**
     * Get chapters (Story node) from the database where the title matches the input
     * 
     * @param String    $input            the input typed in the research bar
     * @param any       $sort             the sort type wanted
     * 
     * @return false|PDOStatement        query results
     */
    public function getStoryNodeByResearch(String $input, $sort) {
        $req = "SELECT * FROM StoryNode sn, Story s WHERE sn.StoryNodeSource = s.StoryId AND sn.StoryNodeTitle LIKE ?";

        switch($sort){
            case "inorder":
                $req .= " ORDER BY StoryTitle ASC";
                break;

            case "inverse":
                $req .= " ORDER BY StoryTitle DESC";
                break;

            case "like":
                $req .= " ORDER BY StoryNodeLikes DESC";
                break;

            case "recent":
                $req .= " ORDER BY StoryNodePublishedAt ASC";
                break;

            default:
                break;
        }
        
        $res = $this->queryAll($req, ['%' . ($input == null ? '' : $input) . '%']);

        return $res;
    }
}

?>
