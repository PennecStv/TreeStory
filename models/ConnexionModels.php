<?php

class Connexion{
    private $_bdd = null;
    private static $_instance = null;

    private function __construct(){
        $this->_bdd = new PDO('mysql:host='.BD_HOST.'; dbname='.BD_DBNAME.'; charset=utf8', BD_USER, BD_PWD);
        $this->_bdd -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION).
    }

    private function __clone(){
    }

    private function __wakeup(){
    }

    /**
     * Donne l'instance actuelle et crée une autre si celle-ci est nulle
     */
    public static function getInstance(){
        if(is_null(self::$_instance)){
            self::$_instance = new Connexion();
        }
        return self::$_instance;
    }

    /**
     * Permet d'obtenir la connexion à la BD
     */
    public function getBdd(){
        return $this->_bdd;
    }
}

?>