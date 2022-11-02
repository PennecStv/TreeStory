<?php
require_once(PATH_MODELS.'ConnexionModels.php');

abstract class DAO{
    private $_erreur;
    private $_debug;

    public function __construct($debug){
        $this -> _debug = $debug;
    }

    /**
     * permet d'identifier le type d'erreur
     */
    public function getErreur(){
        return $this->_erreur;
    }

    /**
     * éxcuter les requêtes sql, on laisse $args à null pour favoriser
     * les requêtes préparées
     */
    private function _requete($sql, $args = null){
        if($args == null){//exécution directe
            $pdos = Connexion::getInstance()->getBdd()->query($sql);
        }
        else{// requête préparée
            $pdos = Connexion::getInstance()->getBdd()->prepare($sql);
            $pdos -> execute($args);
        }
        return $pdos;
    }

    /**
     * résultat avec un tableau à 1D, un seul enregistrement
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
     * résultat avec un tableau 2D, plusieurs enregistrements
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