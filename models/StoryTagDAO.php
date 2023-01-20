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
class StoryTagDAO extends DAO {

    public function getStoryTag(String $condition, $column) {
        if($column == NULL) {
            $req = "SELECT * FROM StoryTag";
            $res = $this->queryAll($req);
        } else {
            $req = "SELECT * FROM StoryTag WHERE StoryTagId = ?";
            $res = $this->queryAll($req, [$condition]);
        }

        return $res;
    }



    public function getStoryTagByStoryId($storyId) {
        $req = "SELECT StoryTagTitle FROM StoryTag NATURAL JOIN StoryTagRelation WHERE StoryId = ?";
        
        $res = $this->queryAll($req, [$storyId]);

        return $res;
    }

}

?>