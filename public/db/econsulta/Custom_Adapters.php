<?php
function connect(){
	$dsn = 'mysql:host=159.203.111.108;dbname=ece_pharmacy_tree;';
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
	 return $dbh;
}
function  CUSTOM_RETRIEVAL($Stored_Procedure,$Params){
	$dbh=connect();
	$dbh->setAttribute(PDO::ATTR_ERRMODE,
 	PDO::ERRMODE_EXCEPTION);

	$sql="call $Stored_Procedure";
	$stmt = $dbh->prepare($sql);
	foreach( $Params as $key => $val ){
		$stmt->bindValue($key, $val, PDO::PARAM_STR);
	}
	$stmt->execute();
	return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>