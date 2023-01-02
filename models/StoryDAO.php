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
        $req = "SELECT count(StoryId) as nbStoryCreate FROM story";
        $res = $this->queryRow($req);
        return $res['nbStoryCreate'];
    }

}

?>
