<?php

namespace Database;

require_once(PATH_UTILS_DATABASE . "Connection.php");

use \PDOException;
use \PDOStatement;

/**
 * DAO represents a pattern for data operations.
 * 
 * @author Idrissa Sall <idrissa.sall@etu.univ-lyon1.fr>
 */

abstract class DAO {

    private $error = null;
    private $debug;

    /**
     * Registers a new DAO instance (implemented in another class).
     * 
     * @param   bool    $debug  Whether or not the DAO debugs everything.
     */
    public function __construct($debug){
        $this->debug = $debug;
    }

    /**
     * Returns the last error or null if none occured.
     * 
     * @return  PDOException    PDOException instance representing the last error or null if none occured.
     */
    public function getLastError(){
        return $this->error;
    }

    /**
     * Executes given SQL statement with given arguments.
     * 
     * @param   string  $sql    SQL Query String.
     * @param   array   $args   SQL Query arguments.
     * 
     * @return  PDOStatement|false  PDOStatement instance representing the result or false if an error occured.
     */
    private function execute($sql, $args = null){
        $db = Connection::getInstance()->getDb();

        if ($args == null) {
            return $db->query($sql);
        } else {
            $req = $db->prepare($sql);
            $req->execute($args);
            return $req;
        }
    }

    /**
     * Executes a given SQL Query String and return the first (only) row.
     * 
     * @param   string  $sql    SQL Query String.
     * @param   array   $args   SQL Query arguments.
     * 
     * @return  array|false     Request-specific array representing the output of the request or false if an error occured.
     */
    public function queryRow($sql, $args = null){
        try {
            $pdos = $this->execute($sql, $args);
            $res = $pdos->fetch();
            $pdos->closeCursor();
        } catch (PDOException $err) {
            if($this->debug) {
                die($err->getMessage());
            }
            $this->error = $err;
            $res = false;
        }

        return $res;
    }

    /**
     * Executes a given SQL Query String and return the rows.
     * 
     * @param   string  $sql    SQL Query String.
     * @param   array   $args   SQL Query arguments.
     * 
     * @return  array|false     Array of request-specific arrays representing the output of the request, or false if an error occured.
     */
    public function queryAll($sql, $args = null){
        try {
            $pdos = $this->execute($sql, $args);
            $res = $pdos->fetchAll();
            $pdos->closeCursor();
        } catch (PDOException $err) {
            if($this->debug){
                die($err->getMessage());
            }
            $this->error = $err;
            $res = false;
        }

        return $res;
    }

    public function insert($sql, $args = null) {
        try {
            return $this->execute($sql, $args);
        } catch (PDOException $err) {
            if($this->debug){
                die($err->getMessage());
            }
            $this->error = $err;
            return false;
        }
    }

}

?>