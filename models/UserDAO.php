<?php

namespace Models;

require_once(PATH_MODELS.'DAO.php');

/**
 * It extends from class DAO. It defines methods for 
 * retrieving specific data from the database
 * 
 * @author Idrissa Sall <idrissa.sall@etu.univ-lyon1.fr>
 */
class UserDAO extends DAO{
    
    /**
     * We access the data of a user knowing his userName
     * 
     * @return false|PDOStatement        query results
     */
    public function getUser(String $userName){
        $requete = "SELECT * FROM User where UserName = '$userName'";
        return $this->queryAll($this->$requete);
    }
}

?>