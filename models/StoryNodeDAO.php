<?php

namespace Models;

require_once(PATH_UTILS_DATABASE.'DAO.php');

use Database\DAO;

/**
 * It extends from class DAO. It defines methods for 
 * retrieving specific data from the database.
 * 
 * @author Rudy Boullier    <rudy.boullier@etu.univ-lyon1.fr>
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
        $req = "SELECT * FROM StoryNode sn, Story s WHERE sn.StoryNodeSource = s.StoryId AND sn.StoryNodeAuthor = '$userName'";
        return $this->queryAll($req);
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

}

?>
