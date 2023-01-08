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


    /**
     * Get main stories of the database where the title matches the input
     * 
     * @param String                     the input typed in the research bar
     * 
     * @return false|PDOStatement        query results
     */
    public function getStoryByResearch(String $input) {
        
        if ($input == null){
            $req = "SELECT * FROM story";
        } else {
            $req = "SELECT * FROM story WHERE StoryTitle LIKE '%$input%'";
        }
        
        $res = $this->queryAll($req);

        return $res;
    }


    /**
     * Get chapters (Story node) from the database where the title matches the input
     * 
     * @param String                     the input typed in the research bar
     * 
     * @return false|PDOStatement        query results
     */
    public function getStoryNodeByResearch(String $input) {
        
        if ($input == null){
            $req = "SELECT * FROM storynode";
        } else {
            $req = "SELECT * FROM storynode WHERE StoryNodeTitle LIKE '%$input%'";
        }
        
        $res = $this->queryAll($req);

        return $res;
    }
}

?>
