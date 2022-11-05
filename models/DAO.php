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
     *identify and give the type of error
     * @return PDOException               
     */
    public function getErreur(){
        return $this->_erreur;
    }

    /**
     * execute SQL prepared statements
     * 
     * @return PDOStatement
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
     * query result in a 1D array, single record
     * 
     * @return false|PDOStatement            
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
     * query result in a 2D array, multiple record
     * 
     * @return false|PDOStatement 
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
