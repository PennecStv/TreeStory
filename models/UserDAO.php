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



    
    /* == Getter == */

    /**
     * We access the data of a user knowing his userName
     * 
     * @return false|PDOStatement        query results
     */
    public function getUser(String $userName){
        $requete = "SELECT * FROM User where UserName = '$userName'";
        return $this->queryRow($requete);
    }


    /* == Setter == */

    /**
     * Changing the username if the new one doesn't currently exist in the database
     */
    public function setUserName(String $oldUserName, String $newUserName){

        $userRequete = "SELECT UserName FROM User WHERE UserName = $newUserName";
        $result = $this -> queryAll($this->$userRequete);

        if ($result->rowCount() > 0 ){

            $updateRequete = "UPDATE User SET UserName = $newUserName WHERE UserName = $oldUserName";
        
        }

    }


    /**
     * Changing the email of the user to another
     */
    public function setMail(String $userName, String $newMail){

        /**
         * Voir validité de l'email
         */

        $updateRequete = "UPDATE User SET UserMail = $newMail WHERE UserName = $userName";
        
    }


    /**
     * Changing the password of the user to a different valid one
     */
    public function setPassword(String $userName, String $oldPassword, String $newPassword){

        if (verifPassword($newPassword)){

            if ($newPassword != $oldPassword){
            
            }

            //Password changed succesfully
            $updateRequete = "UPDATE user SET UserPassword = $newPassword WHERE UserName = $userName";
        } else {

        }
    }


    /**
     * Changing the avatar of the user to another image
     */
    public function setAvatar(String $userName){
        
    }


    /**
     * Making modification to the biography of the user
     */
    public function setBiographie(String $userName){
        
    }



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
