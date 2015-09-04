<?php
	//$con=mysql_connect("sql208.0fees.us","0fe_15099609","trusted143","0fe_15099609_ece_pharmacy_tree");
	//Check connection
	//if (mysqli_connect_errno()) {
	  //echo "Failed to connect to MySQL: " . mysqli_connect_error();
	//}

// Connecting to mysql database
        $con = mysql_connect("sql208.0fees.us", "0fe_15099609", "trusted143") or die(mysql_error());
 
        // Selecing database
        $db = mysql_select_db("0fe_15099609_ece_pharmacy_tree") or die(mysql_error()) or die(mysql_error());

?>