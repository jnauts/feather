<?php

$time_start = microtime(true);
  

class DB {
  
  /*
   * Class for CRUD methods.
   */
  
  private $pdo = NULL;
  private $stmt = NULL;
  
  private $action = NULL;
  
  private $table = "";
  private $where = "";
  private $columns = "";
  
  private $host     = "localhost";
  private $database = "rest";
  private $user     = "root";
  private $password = "";
  
  const INSERT = "INSERT";
  const SELECT = "SELECT";
  const UPDATE = "UPDATE";
  const DELETE = "DELETE";
  
   /*
    * Constructor
    */
  function __construct() {
    try {
     
      $this->pdo = new PDO("mysql:host=$this->host;dbname=$this->database", $this->user, $this->password);
      
      $this->pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
      $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    } catch (PDOException $e) {
      echo $e->getMessage();
      die();
    }
    
  
  }
  
  public function select($columns) {
    $this->columns = $columns;
    $this->action = "SELECT";
    return $this;
  }
  
  private function query() {
    try {
      $sql = '';
      switch ($this->action) {
        case self::SELECT:
          $sql = "SELECT $this->columns FROM $this->table$this->where";
          break;
      }
      echo $sql . "<br>";
      return $this->stmt = $this->pdo->prepare($sql);
    } catch (Exception $e) {
      echo $e;
    }
  }
  
  public function execute($table) {
    $this->table = $table;
    if ($this->stmt==NULL)
      $this->stmt = $this->query();
    
    try {
      
    } catch(Exception $e) {
      echo $e;
    }
    return $this->stmt->query();
  }
  
  /*
   * For bind the values for PDO.
   * Ofir Attia
   * http://php.net/manual/en/pdostatement.bindparam.php
   * 
   */
  private function bind($parameter, $value, $var_type = NULL) {
    
    if ($this->stmt==NULL)
      $this->stmt = $this->query();
    
    if (is_NULL($var_type)) {
        switch (true) {
            case is_bool($value):
                $var_type = PDO::PARAM_BOOL;
                break;
            case is_int($value):
                $var_type = PDO::PARAM_INT;
                break;
            case is_NULL($value):
                $var_type = PDO::PARAM_NULL;
                break;
            default:
                $var_type = PDO::PARAM_STR;
        }
    }
    $stmt->bindValue($parameter, $value, $var_type);
  }
}



$x = new DB();
$ar = array('status'=>1, 'title'=>"");

$y = $x->select("title")->execute('posts');

var_dump($y);

echo '<br><br><b>Total Memory Usage:</b> '.(memory_get_usage(true))/1024 . ' Kb'; // 123 kb

$time_end = microtime(true);
echo '<br><br><b>Total Execution Time:</b> '.($time_end - $time_start).' Mins';

?>