<?php

namespace Models;

require_once(PATH_UTILS_DATABASE.'DAO.php');
use Database\DAO;

/**
 * It extends from class DAO. It defines methods for 
 * retrieving specific data from the database
 * 
 * @author Idrissa Sall <idrissa.sall@etu.univ-lyon1.fr>
 * @author Steve Pennec <steve.pennec@etu.univ-lyon1.fr>
 */
class UserDAO extends DAO{
    
    /**
     * Insert a User into the Database
     * 
     * @return false|PDOStatement        query results
     */
    public function insertUser(String $userName, String $userPassword, String $userMail){

        $requete = "INSERT INTO User (UserName, UserMail, UserPassword, UserCreatedAt) VALUES ('$userName', '$userMail', '$userPassword', CURDATE() )";

        $this->queryRow($requete);

    }


    /**
     * Delete a User from the Database
     * 
     * @return false|PDOStatement        query results
     */
    public function deleteUser(String $userName){
        $requete = "DELETE FROM user WHERE UserName = '$userName'";
        
        $this->queryRow($requete);
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
    public function getUser($condition, $column){
        if($column == "token"){
            $requete = "SELECT * FROM User WHERE UserToken = '$condition'";
        }
        else{
            $requete = "SELECT * FROM User WHERE UserName = '$condition'";
        }

        return $this->queryRow($requete);
    }


    /**
     * Get the hashed password of a specific user
     * 
     * @param String    the Username of the user
     * 
     * @return false|PDOStatement        query results
     */
    public function getPassword(String $userName){
        $requete = "SELECT UserPassword FROM user WHERE UserName = '$userName'";
        return $this->queryRow($requete);
    }

    /**
     * Get the mail of a specific user
     * 
     * @param String    the Username of the user
     * 
     * @return false|PDOStatement        query results
     */
    public function getMail(String $userName){
        $requete = "SELECT UserMail FROM user WHERE UserName = '$userName'";
        return $this->queryRow($requete);
    }

    /**
     * Get the profil picture of a specific user
     * 
     * @param String    the Username of the user
     * 
     * @return false|PDOStatement        query results
     */
    public function getAvatar(String $userName){
        $requete = "SELECT UserAvatar FROM user WHERE UserName = '$userName'";
        return $this->queryRow($requete);
    }


    /**
     * Get the biography of a specific user
     * 
     * @param String    the Username of the user
     * 
     * @return false|PDOStatement        query results
     */
    public function getBiography(String $userName){
        $requete = "SELECT UserBiography FROM user WHERE UserName = '$userName'";
        return $this->queryRow($requete);
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
    public function setUser($column, $value, $condition){
        $requete = "UPDATE User SET $column = '$value' WHERE UserName = '$condition'";
        $this->queryRow($requete);
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
    public static function verifPassword($password){
        //Password Regex
        $upperCase      = preg_match('@[A-Z]@', $password);
        $lowerCase      = preg_match('@[a-z]@', $password);
        $number         = preg_match('@[0-9]@', $password);
        $specialChar    = preg_match('@[^\w]@', $password);
        $passwordLenght = strlen($password) > 7;

        if ($upperCase && $lowerCase && $number && $specialChar && $passwordLenght){ //En remplaçant les && par des +, on obtient 5 si tout est vérifié
            return true;
        } else {
            return false;
        }
    }

}

?>
