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


    /**
     * Get main stories of the database where the title matches the input
     * 
     * @param String    $input            the input typed in the research bar
     * @param any       $sort             the sort type wanted
     * 
     * @return false|PDOStatement        query results of the story table + the published date of its first chapter
     */
    public function getStoryByResearch(String $input, $sort) {
        
        if ($input == null){
            $req = "SELECT s.*, st.StoryNodePublishedAt FROM story s NATURAL JOIN storynode st WHERE st.StoryNodeSource = s.StoryId AND StoryNodeRoot IS NULL";
        } else {
            $req = "SELECT s.*, st.StoryNodePublishedAt FROM story s NATURAL JOIN storynode st WHERE st.StoryNodeSource = s.StoryId AND StoryNodeRoot IS NULL AND StoryTitle LIKE '%$input%'";
        }

        switch($sort){
            case "inorder":
                $req .= " ORDER BY StoryTitle ASC";
                break;

            case "inverse":
                $req .= " ORDER BY StoryTitle DESC";
                break;

            case "recent":
                $req .= " ORDER BY StoryNodePublishedAt ASC";
                break;

            default:
                break;
        }
        
        $res = $this->queryAll($req);

        return $res;
    }


    /**
     * Get the number of story nodes that the story has.
     * 
     * @param String       $storyId      ID of the main story
     * 
     * @return false|PDOStatement        query results
     */
    public function getNbStoryNode(int $storyId) {
        $req = "SELECT COUNT(storynode.StoryNodeId) as nbStoryNode FROM storynode WHERE storynode.StoryNodeSource = $storyId";

        $res = $this->queryRow($req);

        return $res["nbStoryNode"];
    }
}
?>