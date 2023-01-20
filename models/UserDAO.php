<?php

namespace Models;

require_once(PATH_UTILS_DATABASE.'DAO.php');
use Database\DAO;

/**
 * It extends from class DAO. It defines methods for 
 * retrieving specific data from the database
 * 
 * @author  Idrissa Sall    <idrissa.sall@etu.univ-lyon1.fr>
 * @author  Steve Pennec    <steve.pennec@etu.univ-lyon1.fr>
 * @author  Rudy Boullier   <rudy.boullier@etu.univ-lyon1.fr>
 * @author  Idrissa Sall    <idrissa.sall@etu.univ-lyon1.fr>
 * @author  Steve Pennec    <steve.pennec@etu.univ-lyon1.fr>
 * @author  Rudy Boullier   <rudy.boullier@etu.univ-lyon1.fr>
 */
class UserDAO extends DAO {
    
    /**
     * Insert a User into the Database
     * 
     * @return false|PDOStatement        query results
     */
    public function insertUser(String $userName, String $userPassword, String $userMail) {
        $requete = "INSERT INTO User (UserName, UserMail, UserPassword, UserCreatedAt) VALUES (?, ?, ?, CURDATE() )";
        $this->queryRow($requete, [$userName, $userMail, $userPassword]);
    }

    /**
     * Delete a User from the Database
     * 
     * @return false|PDOStatement        query results
     */
    public function deleteUser(String $userName) {
        $requete = "DELETE FROM User WHERE UserName = ?";
        $this->queryRow($requete, [$userName]);
    }

    

    /* == Getter == */

    /**
     * We access the data of a user knowing his userName 
     * or his token if it is not null.
     * 
     * @param String    the condition that we want to satisfy in the WHERE.
     * @param String    the column that we need.
     * 
     * @return false|PDOStatement        query results
     */
    public function getUser(String $condition, $column) {
        if($column == "token") {
            $requete = "SELECT * FROM User WHERE UserToken = ?";
        } else {
            $requete = "SELECT * FROM User WHERE UserName = ?";
        }
        return $this->queryRow($requete, [$condition]);
    }


     /**
     * Get users from the database where the title matches the input
     * 
     * @param String                     the input typed in the research bar
     * 
     * @return false|PDOStatement        query results
     */
    public function getUsersByResearch(String $input, $sort) {
        $req = "SELECT * FROM User WHERE UserName LIKE ?";

        switch($sort){
            case "inorder":
                $req .= " ORDER BY UserName ASC";
                break;

            case "inverse":
                $req .= " ORDER BY UserName DESC";
                break;

            case "like":
                $req .= "";
                break;

            case "view":
                $req .= "";
                break;

            case "recent":
                $req .= "";
                break;

            default:
                break;
        }
        
        $res = $this->queryAll($req, ['%' . ($input == null ? '' : $input) . '%']);

        return $res;
    }


    /**
     * Get the hashed password of a specific user
     * 
     * @param String    the Username of the user
     * 
     * @return false|PDOStatement        query results
     */
    public function getPassword(String $userName) {
        $requete = "SELECT UserPassword FROM User WHERE UserName = ?";
        return $this->queryRow($requete, [$userName]);
    }

    /**
     * Get the mail of a specific user
     * 
     * @param String    the Username of the user
     * 
     * @return false|PDOStatement        query results
     */
    public function getMail(String $userName) {
        $requete = "SELECT UserMail FROM User WHERE UserName = ?";
        return $this->queryRow($requete, [$userName]);
    }

    /**
     * Get the profil picture of a specific user
     * 
     * @param String    the Username of the user
     * 
     * @return false|PDOStatement        query results
     */
    public function getAvatar(String $userName) {
        $requete = "SELECT UserAvatar FROM User WHERE UserName = ?";
        return $this->queryRow($requete, [$userName]);
    }

    /**
     * Get the biography of a specific user
     * 
     * @param String    the Username of the user
     * 
     * @return false|PDOStatement        query results
     */
    public function getBiography(String $userName) {
        $requete = "SELECT UserBiography FROM User WHERE UserName = ?";
        return $this->queryRow($requete, [$userName]);
    }

    /**
     * Get the total number of registered users.
     * 
     * @return false|PDOStatement        query results
     */
    public function getNbRegistered() {
        $req = "SELECT count(UserName) as nbRegistered FROM User";
        $res = $this->queryRow($req);
        return $res['nbRegistered'];
    }


    /* == Setter == */

    /**
     * We modify a single column of table User.
     * 
     * @param String    the column we need to set.
     * @param String    the value.
     * @param String    the condition that we want to satisfy in the WHERE.
     * 
     * @return false|PDOStatement        query results
     */
    public function setUser($column, $value, $condition) {
        $requete = "UPDATE User SET ? = ? WHERE UserName = ?";
        $this->queryRow($requete, [$column, $value, $condition]);
    }


    /* == Useful Methods == */
    /**
     * gives a string for the password reset token.
     * 
     * @return String   the random token.
     */
    public function getRandomToken() { 
        $randomToken = '';
        $str = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';  
      
        for ($i = 0; $i <40; $i++) { 
            $index = rand(0, strlen($str) - 1); 
            $randomToken .= $str[$index]; 
        }

        return $randomToken;
    }

    /**
     * Verify if the password contains all required characters and length.
     * 
     * @return Boolean True if the password pass the regex, False if not
     */
    public static function verifPassword($password) {
        //Password Regex
        $upperCase      = preg_match('@[A-Z]@', $password);
        $lowerCase      = preg_match('@[a-z]@', $password);
        $number         = preg_match('@[0-9]@', $password);
        $specialChar    = preg_match('@[^\w]@', $password);
        $passwordLenght = strlen($password) > 7;

        if ($upperCase && $lowerCase && $number && $specialChar && $passwordLenght) { //En remplaçant les && par des +, on obtient 5 si tout est vérifié
            return true;
        } else {
            return false;
        }
    }


    /**
     * Gives the number of follow by knowing his user name.
     */
    public function getFollowers($column, $userId){
        $requete = "SELECT * FROM UserFollowerRelation WHERE ? = ?";
        return $this->queryAll($requete, [$column, $userId]);
    }

    /**
     * Gives the number of follow between two users.
     */
    public function getFollows($userId, $followingUserId){
        $requete = "SELECT * FROM UserFollowerRelation WHERE UserId = ? AND FollowingUserId = ?";
        return $this->queryRow($requete, [$userId, $followingUserId]);
    }


    /**
     * Insert a follow relation between two users.
     */
    public function insertFollowRelation($userId,$followingUserId){
        $requete = "INSERT INTO UserFollowerRelation (UserId, FollowingUserId) VALUES (?, ?)";
        $this->queryRow($requete, [$userId, $followingUserId]);
    }

    
    /**
     * Delete a follow relation between two users.
     */
    public function deleteFollowRelation($userId, $followingUserId){
        $requete = "DELETE FROM UserFollowerRelation WHERE UserId = ? AND FollowingUserId = ?";
        $this->queryRow($requete, [$userId, $followingUserId]);
    }


    /**
     * this function retrieves the subscriptions of a specified user using an SQL query. 
     * It takes two arguments as input:
     * @param String : the column of the table userFollowerRelation that we want to use in the query
     * @param String : the value of the column that we want to use in the query
     * @return false|PDOStatement        query results
     */
    public function getUsersSubscribers($column,$userId){
        $requete = "SELECT UserName,UserAvatar,UserBiography FROM UserFollowerRelation INNER JOIN User ON User.UserName = UserFollowerRelation.$column WHERE FollowingUserId=?"; 
        return $this->queryAll($requete, [$userId]);
    }

    
    /**
     * this function retrieves the subscribers of a specified user using an SQL query. 
     * It takes two arguments as input:
     * @param String : the column of the table userFollowerRelation that we want to use in the query
     * @param String : the value of the column that we want to use in the query
     * @return false|PDOStatement        query results
     */
    public function getUsersSubscriptions($column,$userId){
        $requete = "SELECT UserName,UserAvatar,UserBiography FROM UserFollowerRelation INNER JOIN User ON User.UserName = UserFollowerRelation.$column WHERE UserId=?"; 
        return $this->queryAll($requete, [$userId]);
    }
}

?>
