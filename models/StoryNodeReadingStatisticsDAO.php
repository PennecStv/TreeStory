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


    /**
     * get all favorites of a storyNode
     * 
     * @param   string  $userName   Username of the users
     * 
     * @return  false|PDOStatement        query results
     */
    public function getFavorites(int $userName) {
        return $this->queryAll("SELECT StotyNodeTitle, StoryNodeId FROM StoryNodeReadingStatistics INNER JOIN StoryNode ON StoryNode.StoryNodeId = StoryNodeReadingStatistics.StoryNodeReadingStatisticsSubject WHERE StoryNodeReadingStatisticsUser = ?", [$userName]);    
    }


    /**
     * add a favorite to a storyNode
     */
    public function addFavorite(int $storyNodeId , String $username) {
        $this->insert("INSERT INTO StoryNodeReadingStatistics (StoryNodeReadingStatisticsSubject, StoryNodeReadingStatisticsUser) VALUES (?, ?);", [$storyNodeId,$username]);
    }


    /**
     * remove a favorite to a storyNode
     */
    public function removeFavorite(int $storyNodeId , String $username) {
        $this->queryRow("DELETE FROM StoryNodeReadingStatistics WHERE StoryNodeReadingStatisticsSubject = ? AND StoryNodeReadingStatisticsUser = ?", [$storyNodeId,$username]); 
    }

}

?>
