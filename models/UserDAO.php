<?php
require_once(PATH_MODELS.'DAO.php');

class UserDAO extends DAO{
    
    /**
     * Retourne l'enregistrement d'un user à partir de son userName 
     */
    public function getUser(String $userName){
        $requete = "SELECT * FROM User where UserName = '$userName'";
        return $this->queryAll($this->$requete);
    }
}

?>