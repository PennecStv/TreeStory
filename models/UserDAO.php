<?php

namespace Models;

require_once(PATH_UTILS_DATABASE.'DAO.php');
use Database\DAO;

/**
 * It extends from class DAO. It defines methods for 
 * retrieving specific data from the database
 * 
 * @author Idrissa Sall <idrissa.sall@etu.univ-lyon1.fr>
 */
class UserDAO extends DAO{
    
    /**
     * We access the data of a user knowing his userName 
     * or his token if it is not null.
     * 
     * @param String    the condition that we want to satisfy in the WHERE.
     * @param String    the column that we need.
     * 
     * @return false|PDOStatement        query results
     */
    public function getUser($condition,$column){
        if($column == "token"){
            $requete = "SELECT * FROM User WHERE UserToken = '$condition'";
        }
        else{
            $requete = "SELECT * FROM User WHERE UserName = '$condition'";
        }
        
        return $this->queryRow($requete);
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
}

?>
