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
        $requete = "SELECT * FROM User where UserName = '$userName'";
        return $this->queryAll($this->$requete);
    }


    /* == Setter == */

    /**
     * Changing the username if the new one doesn't currently exist in the database
     */
    public function setUserName(String $userName){

    }


    /**
     * Changing the email of the user to another
     */
    public function setMail(String $userName){

    }

    /**
     * Changing the password of the user to a different valid one
     */
    public function setPassword(String $userName){

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
