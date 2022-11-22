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
     * 
     */
    public function insertUser(String $userName, String $userPassword, String $userMail){

        $requete = "INSERT INTO User (UserName, UserMail, UserPassword, UserCreatedAt) VALUES ('$userName', '$userMail', '$userPassword', CURDATE() )";

        $this->queryRow($requete);

    }


    /**
     * 
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
    public function getUser(String $userName){
        $requete = "SELECT * FROM user WHERE UserName = '$userName'";
        return $this->queryRow($requete);
    }

    /**
     * 
     */
    public function getPassword(String $userName){
        $requete = "SELECT UserPassword FROM user WHERE UserName = '$userName'";
        return $this->queryRow($requete);
    }

    /**
     * 
     */
    public function getMail(String $userName){
        $requete = "SELECT UserMail FROM user WHERE UserName = '$userName'";
        return $this->queryRow($requete);
    }

    /**
     * 
     */
    public function getAvatar(String $userName){
        $requete = "SELECT UserAvatar FROM user WHERE UserName = '$userName'";
        return $this->queryRow($requete);
    }


    /**
     * 
     */
    public function getBiography(String $userName){
        $requete = "SELECT UserBiography FROM user WHERE UserName = '$userName'";
        return $this->queryRow($requete);
    }


    /* == Setter == */

    /**
     * Changing the username if the new one doesn't currently exist in the database
     */
    public function setUserName(String $oldUserName, String $newUserName){

        $updateRequete = "UPDATE user SET UserName = '$newUserName' WHERE UserName = '$oldUserName'";

        $this->queryRow($updateRequete);

    }


    /**
     * Changing the email of the user to another
     */
    public function setMail(String $userName, String $newMail){

        /**
         * Voir validité de l'email
         */

        $updateRequete = "UPDATE user SET UserMail = '$newMail' WHERE UserName = '$userName'";
        
        $this->queryRow($updateRequete);
    }


    /**
     * Changing the password of the user to a different valid one
     */
    public function setPassword(String $userName, String $newPassword){

        $updateRequete = "UPDATE user SET UserPassword = '$newPassword' WHERE UserName = '$userName'";

        $this->queryRow($updateRequete);
    }

    /**
     * We modify a single column of table User.
     * 
     * @param String    the column we need to set.
     * @param String    the value.
     * @param String    the condition that we want to satisfy in the WHERE.
     */
    public function setUser($column, $value, $condition){
        $requete = "UPDATE User SET $column = '$value' WHERE UserName = '$condition'";
        $this->queryRow($requete);
    }


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
     * Changing the avatar of the user to another image
     */
    public function setAvatar(String $userName, String $userAvatar){
              
        $updateRequete = "UPDATE user SET UserAvatar = '$userAvatar' WHERE UserName = '$userName'";

        $this->queryRow($updateRequete);
    }


    /**
     * Making modification to the biography of the user
     */
    public function setBiography(String $userName, String $newBiography){

        $updateRequete = "UPDATE user SET UserBiography = '$newBiography' WHERE UserName = '$userName'";

        $this->queryRow($updateRequete);
    }


    /* == Methods == */

    /**
     * 
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
