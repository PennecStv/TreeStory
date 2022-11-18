<?php

namespace Models;

require_once(PATH_UTILS_DATABASE.'DAO.php');

/**
 * It extends from class DAO. It defines methods for 
 * retrieving specific data from the database
 * 
 * @author Idrissa Sall <idrissa.sall@etu.univ-lyon1.fr>
 */
class UserDAO extends DAO{
    
    
    /* == Getter == */

    /**
     * We access the data of a user knowing his userName
     * 
     * @return false|PDOStatement        query results
     */
    public function getUser(String $userName){
        $requete = "SELECT * FROM User WHERE UserName = '$userName'";
        return $this->queryAll($this->$requete);
    }


    /* == Setter == */

    /**
     * Changing the username if the new one doesn't currently exist in the database
     */
    public function setUserName(String $oldUserName, String $newUserName){

        /**
         * Filtre d'UserName à implémenter
         */

        $userRequete = "SELECT UserName FROM User WHERE UserName = $newUserName";
        $result = $this -> queryAll($this->$userRequete);

        if ($result->rowCount() > 0 ){

            $updateRequete = "UPDATE user SET UserName = $newUserName WHERE UserName = $oldUserName";
        
        }

    }


    /**
     * Changing the email of the user to another
     */
    public function setMail(String $userName, String $newMail){

        /**
         * Voir validité de l'email
         */

        $updateRequete = "UPDATE user SET UserMail = $newMail WHERE UserName = $userName";
        
    }


    /**
     * Changing the password of the user to a different valid one
     */
    public function setPassword(String $userName, String $newPassword){

        /**
         * Voir validité du mot de passe
         */

        //Password Regex
        $upperCase      = preg_match('@[A-Z]@', $newPassword);
        $lowerCase      = preg_match('@[a-z]@', $newPassword);
        $number         = preg_match('@[0-9]@', $newPassword);
        $specialChar    = preg_match('@[^\w]@', $newPassword);
        $passwordLenght = str_length($newPassword) > 7;

        if ($upperCase && $lowerCase && $number && $specialChar && $passwordLenght){
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
}

?>
