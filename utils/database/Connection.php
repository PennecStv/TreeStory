<?php

namespace Database;

use PDO;

/**
 * Connection stores the connection with the database.
 * 
 * @author Idrissa Sall <idrissa.sall@etu.univ-lyon1.fr>
 */
class Connection {

    private $db = null;
    private static $instance = null;

    /**
     * Create a new Connection with connection constants.
     */
    private function __construct() {
        $this->db = new PDO('mysql:host='.$_ENV["BD_HOST"].'; dbname='.$_ENV["BD_DBNAME."].'; charset=utf8', $_ENV["BD_USER"], $_ENV["BD_PWD"]);
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    /**
     * Gives the current instance or creates one otherwise.
     * 
     * @return  Connection  The current connection instance.
     */
    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Returns the unique PDO instance created previously.
     * 
     * @return  PDO         PDO instance defined previously.
     */
    public function getDb(){
        return $this->db;
    }
    
}

?>
