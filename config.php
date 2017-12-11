<?php

    // altervista
	
    $databaseHost = 'localhost';
    $databaseName = 'my_francescobognini';
    $databaseUsername = '';
    $databasePassword = '';
	
    // server2go
    /*
    $databaseHost = 'localhost';
    $databaseName = 'crud';
    $databaseUsername = 'root';
    $databasePassword = '';
    */
    
    $mysqli = mysqli_connect($databaseHost, $databaseUsername, $databasePassword, $databaseName); 
    //@mysql_select_db("crud",$mysqli);
?>