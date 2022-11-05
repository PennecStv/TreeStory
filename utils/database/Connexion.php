<?php

namespace Database;

/**
 * Connexion makes the connection between our web site 
 * and the database
 * 
 * @author Idrissa Sall <idrissa.sall@etu.univ-lyon1.fr>
 */

class Connexion{

    private $_bdd = null;
    private static $_instance = null;

    /**
     * Create a new Connexion with connection constants
     */
    private function __construct(){
        $this->_bdd = new PDO('mysql:host='.BD_HOST.'; dbname='.BD_DBNAME.'; charset=utf8', BD_USER, BD_PWD);
        $this->_bdd -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION).
    }

    /**
     * Gives the current instance and creates another one if this one is null
     * 
     * @return  Connexion              a connexion instance
     */
    public static function getInstance(){
        if(is_null(self::$_instance)){
            self::$_instance = new Connexion();
        }
        return self::$_instance;
    }

    /**
     * Gives the PDO instance created in the construct
     * @return  null|PDO     the connection to the Database
     */
    public function getBdd(){
        return $this->_bdd;
    }
}

?>
