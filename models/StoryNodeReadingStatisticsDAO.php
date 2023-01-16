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
class StoryNodeReadingStatisticsDAO extends DAO {

    /**
     * Get the total number of favorite user knowing his userName.
     * 
     * @param String    $userName   Username of the user
     * 
     * @return false|PDOStatement        query results
     */
    public function getStatisticsFavorite(String $userName) {
        $req = "SELECT count(StoryNodeReadingStatisticsFavorite) as nbFavorite FROM StoryNodeReadingStatistics snrs, User u WHERE snrs.StoryNodeReadingStatisticsUser = u.UserName AND u.UserName = '$userName'";
        $res = $this->queryRow($req);
        return $res['nbFavorite'];
    }
}

?>
