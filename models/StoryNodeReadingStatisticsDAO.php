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
        $req = "SELECT count(StoryNodeReadingStatisticsFavorite) as nbFavorite FROM StoryNodeReadingStatistics snrs, User u WHERE snrs.StoryNodeReadingStatisticsUser = u.UserName AND u.UserName = ?";
        $res = $this->queryRow($req, [$userName]);
        return $res['nbFavorite'];
    }


    /**
     * get all favorites of a storyNode
     * 
     * @param   string  $userName   Username of the users
     * 
     * @return  false|PDOStatement        query results
     */
    public function estFavorite(String $userName, int $storyNodeId) {
        return $this->queryRow("SELECT * FROM StoryNodeReadingStatistics WHERE StoryNodeReadingStatisticsUser = ? AND StoryNodeReadingStatisticsSubject = ?", [$userName, $storyNodeId]);    
    }

    /**
     * get all favorites of a storyNode
     * 
     * @param   string  $userName   Username of the users
     * 
     * @return  false|PDOStatement        query results
     */
    public function getFavorites(String $userName) {
        return $this->queryAll("SELECT StoryNodeSource, StoryCover, StoryNodeId, StoryNodeAuthor, StoryNodeText, StoryNodeTitle, StoryNodeLikes  FROM StoryNode, Story, StoryNodeReadingStatistics WHERE StoryNode.StoryNodeId = StoryNodeReadingStatistics.StoryNodeReadingStatisticsSubject AND StoryNodeReadingStatisticsUser = ? AND Story.StoryId = StoryNode.StoryNodeSource", [$userName]);
    }
//        return $this->queryAll("SELECT StoryNodeTitle, StoryNodeId, StoryCover FROM StoryNode, Story, StoryNodeReadingStatistics INNER JOIN StoryNodeReadingStatistics ON StoryNode.StoryNodeId = StoryNodeReadingStatistics.StoryNodeReadingStatisticsSubject WHERE StoryNodeReadingStatisticsUser = ? AND Story.StoryId = StoryNode.StoryNodeSource", [$userName]);

    
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
