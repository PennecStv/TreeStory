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
        $req = "SELECT * FROM storynode sn, story s WHERE sn.StoryNodeSource = s.StoryId AND sn.StoryNodeAuthor = '$userName'";
        return $this->queryAll($req);
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
            $req = "SELECT * FROM storynode sn, story s WHERE sn.StoryNodeSource = s.StoryId";
        } else {
            $req = "SELECT * FROM storynode sn, story s WHERE sn.StoryNodeSource = s.StoryId AND sn.StoryNodeTitle LIKE '%$input%'";
        }
        
        $res = $this->queryAll($req);

        return $res;
    }
}

?>
