<?php
	// Initialize connection variables.
	$host = "localhost";
	$database = "final_project";
	$user = "postgres";
	$password = "1234";

	// Initialize connection object.
	$connection = pg_connect("host=$host dbname=$database user=$user password=$password") 
		or die("Failed to create connection to database: ". pg_last_error(). "<br/>");
	//print "Successfully created connection to database.<br/>";

	
	
?>