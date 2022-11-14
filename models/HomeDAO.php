<?php

namespace Models;

require_once(PATH_UTILS_DATABASE.'DAO.php');
use Database\DAO;

/**
 * It extends from class DAO. It defines methods for 
 * retrieving specific data from the database.
 * 
 * @author Rudy Boullier <rudy.boullier@etu.univ-lyon1.fr>
 */
class HomeDAO extends DAO {
    
    /**
     * We access the data of the total number of stories.
     * 
     * @return String        query results total number of stories created.
     */
    public function getNbStory() { 
        $req = "SELECT count(StoryId) as nbStoryCreate FROM Story";
        $res = $this->queryRow($req);
        return $res['nbStoryCreate'];
    }

    /**
     * We access the data of the total number of registered.
     * 
     * @return String        query results number of registered.
     */
    public function getNbInscrit() {
        $req = "SELECT count(UserName) as nbInscrit FROM User";
        $res = $this->queryRow($req);
        return $res['nbInscrit'];
    }

}

?>
