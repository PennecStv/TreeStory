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
            $req = "SELECT * FROM storytag";
        } else {
            $req = "SELECT * FROM storytag WHERE StoryTagId = '$condition'";
        }
        
        $res = $this->queryAll($req);

        return $res;
    }



    public function getStoryTagByStoryId($storyId) {

        $req = "SELECT StoryTagTitle FROM storytag NATURAL JOIN storytagrelation WHERE StoryId = '$storyId'";
        
        $res = $this->queryAll($req);

        return $res;
    }

}

?>