<?php

/**
 * A class file to connect to database
 */
class DB_CONNECT {

    // constructor
    function __construct() {
        // connecting to database
        $this->connect();
    }

    // destructor
    function __destruct() {
        // closing db connection
        $this->close($this->connect());
    }

    /**
     * Function to connect with database
     */
    function connect() {
        // import database connection variables


        // Connecting to mysql database on localsetup
        // $con = mysqli_connect("localhost","homestead","secret","ece_pharmacy_tree");

        // Connecting to mysql database on production
        $con = mysqli_connect("localhost","root","admin","ece_pharmacy_tree");
        
        //Check connection
        if (mysqli_connect_errno()) {
          echo json_encode(array("mysql_connection_error" => "Failed to connect to MySQL: " . mysqli_connect_error() ));
        }

        // $con = mysql_connect("localhost", "homestead", "secret") or die(mysql_error());

        // Selecing database
        // $db = mysql_select_db("ece_pharmacy_tree") or die(mysql_error()) or die(mysql_error());

        // returing connection cursor
        return $con;
    }

    /**
     * Function to close db connection
     */
    function close($con) {
        // closing db connection
        mysqli_close($con);
    }

}

?>