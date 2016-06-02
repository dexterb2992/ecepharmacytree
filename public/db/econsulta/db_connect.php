<?php
 

function connect() {
    $dsn = 'mysql:host=128.199.83.60;dbname=ece_pharmacy_tree;';
    $user = 'uret1';
    $password = 'urett1';
    $dbh;
     try
     {
      $dbh = new PDO($dsn, $user, $password);
     }
     catch (PDOException $e)
     {
      echo 'Connection failed: ' . $e->getMessage();
     }
     
    $dbh->setAttribute(PDO::ATTR_ERRMODE,
    PDO::ERRMODE_EXCEPTION);
     return $dbh;
}
 
?>