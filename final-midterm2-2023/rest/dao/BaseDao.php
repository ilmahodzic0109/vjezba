<?php
define("DB_NAME", "vjezba");
define("DB_PORT", 3306);
define("DB_USER", "root");
define("DB_PASSWORD", "");
define("DB_HOST", "127.0.0.1");
define('JWT_SECRET', 'Cm[(%=hJNuBn:(7nbmS=Qc&={BA%/F');
class BaseDao {
  private $table;
    protected $connection;

    /**
    * constructor of dao class
    */
    public function __construct($table = null){
      $this->table = $table;
        try {

        /** TODO
        * List parameters such as servername, username, password, schema. Make sure to use appropriate port
        */
        $options=[
          PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
          PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ];
        $this->connection = new PDO(
          "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";port=" . DB_PORT,
          DB_USER,
          DB_PASSWORD, 
          $options
      );

        /** TODO
        * Create new connection
        * Use $options array as last parameter to new PDO call after the password
        */

        // set the PDO error mode to exception
          $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
         // echo "Connected successfully";
        } catch(PDOException $e) {
          echo "Connection failed: " . $e->getMessage();
        }
    }

}
?>