<?php

namespace Models;

require_once(PATH_UTILS_DATABASE.'Connexion.php');

/**
 * The class is used to prepare queries from the database. 
 * We can also identify the associated errors
 * 
 * @author Idrissa Sall <idrissa.sall@etu.univ-lyon1.fr>
 */

abstract class DAO{
    private $_erreur;
    private $_debug;

    public function __construct($debug){
        $this -> _debug = $debug;
    }

    /**
     *
     * @return _erreur               identify the type of error
     */
    public function getErreur(){
        return $this->_erreur;
    }

    /**
     * execute SQL prepared statements
     * 
     * @return PDOStatement         the query results
     */
    private function _requete($sql, $args = null){
        if($args == null){
            $pdos = Connexion::getInstance()->getBdd()->query($sql);
        }
        else{
            $pdos = Connexion::getInstance()->getBdd()->prepare($sql);
            $pdos -> execute($args);
        }
        return $pdos;
    }

    /**
     * result in a 1D array, single record
     * 
     * @return false|res            
     */
    public function queryRow($sql, $args = null){
        try {
            $pdos = $this->_requete($sql, $args);
            $res = $pdos->fetch();
            $pdos->closeCursor();
        } catch (PDOException $err) {
            if($this->_debug){
                die($err -> getMessage());
            }
            $this->_erreur = 'errreur query';
            $res = false;
        }
        return $res;
    }

    /**
     * result in a 1D array, multiple record
     * 
     * @return false|res 
     */
    public function queryAll($sql, $args = null){
        try {
            $pdos = $this->_requete($sql, $args);
            $res = $pdos->fetchAll();
            $pdos->closeCursor();
        } catch (PDOException $err) {
            if($this->_debug){
                die($err -> getMessage());
            }
            $this->_erreur = 'errreur query';
            $res = false;
        }
        return $res;
    }
}
?>