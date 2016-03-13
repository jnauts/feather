<?php

class DB {
  
  /*
   * Database class for the database connection.
   */
  
  private $stmt = null;
   /*
    * Constructor
    */  
  function __construct() {
    $host     = "localhost";
    $database = "rest";
    $user     = "root";
    $password = "";
  
    try {
      
      $this->stmt = new PDO("mysql:host=$host;dbname=$database", $user, $password);
      $this->stmt->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
      $this->stmt->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      
    } catch (PDOException $e) {
      echo $e->getMessage();
      die();
    }
  }
  
  /*
   * Method for insert data.
   */
  
  public function insert($data, $table) {
    /*
     * Implode all the columns and placeholders
     */
    $columns   = implode(", ", array_keys($data));
    $values   = ":" . implode(", :", array_keys($data));

    $stmt = $this->stmt->prepare("INSERT INTO $table ($columns) VALUES ($values)");

    $data = array_values($data);

    /*
     * Bind parameters
     */
    for($x=0; $x<count($data);$x++) {
      $this->bind($x+1, $data[$x], $stmt);
    }

    /*
     * Return the execution success
     */
    return $stmt->execute();	
  }
  
  /*
   * Method for delete data.
   */
  public function delete($table, $where=NULL) {
    
    $columns = null;
    
    /*
     * Generate where columns and placeholders
     */
    
    if (is_array($where)) {
      $delimiter = "=?";
      foreach(array_keys($where) as $e) {
        $columns[] = $e . $delimiter;
      }
    } else {
      throw new Exception("where is not an array");
    }
    
    $sql = "DELETE FROM $table";
    
    if ($where != null)
      $sql .= " WHERE " . implode(" AND ", $columns);
      
    $stmt = $this->stmt->prepare($sql);
    
    /*
     * Bind parameters
     */
    $where = array_values($where);
    for($x=0; $x<count($where);$x++) {
      $this->bind($x+1, $where[$x], $stmt);
    }

    /*
     * Return the execution success
     */
    return $stmt->execute();	
  }
  
  /*
   * Method for select data.
   */
  public function select($table, $column="*", $where="", $gate="AND") {
    
    $columns = null;
    
    /*
     * Generate where columns and placeholders
     */
    
    if (is_array($where)) {
      $delimiter = "=?";
      foreach(array_keys($where) as $e) {
        $columns[] = $e . $delimiter;
      }
    } else {
      throw new Exception("where is not an array");
    }
    
    if ($table==NULL)
      throw new Exception("No table is selected");
    
    $sql = "SELECT $column FROM $table";
    
    if ($where != null)
      $sql .= " WHERE " . implode(" " . strtoupper($gate) . " ", $columns);
    
    echo $sql;
    $stmt = $this->stmt->prepare($sql);
    
    /*
     * Bind parameters
     */
    $where = array_values($where);
    for($x=0; $x<count($where);$x++) {
      $this->bind($x+1, $where[$x], $stmt);
    }

    /*
     * Return the execution success
     */
    var_dump($stmt->excute($where));
  }
  
  /*
   * For bind the values for PDO.
   * Ofir Attia
   * http://php.net/manual/en/pdostatement.bindparam.php
   * 
   */
  private function bind($parameter, $value, $stmt, $var_type = null) {
    if (is_null($var_type)) {
        switch (true) {
            case is_bool($value):
                $var_type = PDO::PARAM_BOOL;
                break;
            case is_int($value):
                $var_type = PDO::PARAM_INT;
                break;
            case is_null($value):
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

$time_start = microtime(true);
$x->select("posts", '*', $ar);

echo '<br><br><b>Total Memory Usage:</b> '.(memory_get_usage(true))/1024 . ' Kb'; // 123 kb

$time_end = microtime(true);
echo '<br><br><b>Total Execution Time:</b> '.($time_end - $time_start).' Mins';

?>