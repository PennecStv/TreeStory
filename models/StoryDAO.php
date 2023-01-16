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
class StoryDAO extends DAO {

    /**
     * We access the data of the total number of stories.
     * 
     * @return false|PDOStatement        query results
     */
    public function getNbStory() { 
        $req = "SELECT count(StoryId) as nbStoryCreate FROM Story";
        $res = $this->queryRow($req);
        return $res['nbStoryCreate'];
    }
    
    public function get_tags() {
        return $this->queryAll("SELECT StoryTagId as id, StoryTagTitle as title FROM StoryTag");
    }

    public function add_tag(int $storyId, int $tagId) {
        return $this->queryRow("INSERT INTO StoryTagRelation (StoryId, StoryTagId) VALUES (?, ?);", [$storyId, $tagId]);  
    }

    public function create(string $title, string $cover = null) {
        $success = $this->insert("INSERT INTO Story (StoryTitle, StoryCover) VALUES (?, ?);", [$title, $cover]);

        if ($success) return $this->queryRow("SELECT * FROM Story WHERE StoryId = (SELECT max(StoryId) FROM Story);");
        return false;
    }

    public function get(int $storyId) {
        return $this->queryRow("SELECT * FROM Story WHERE StoryId = ?", [$storyId]);
    }

}

?>
