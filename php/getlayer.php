<?php
	require_once('db_connect.php');

	// POST Data
	$layer = $_POST['layer'];
	
	$query="";
	unset($result_set);
	
	if( $layer=="countyLayer" ){
		$query = "SELECT countyname, ST_AsGeoJSON(geom)
					FROM county_moi_1090820;";
	}
	if( $layer=="districtLayer" ){
		$query = "SELECT countyname, townname, ST_AsGeoJSON(geom)
					FROM town_moi_1111118;";
	}
	
	
	//echo json_encode($query, JSON_UNESCAPED_UNICODE);
	
	$result_set = pg_query($connection, $query) 
		or die("Encountered an error when executing given sql statement: ". pg_last_error(). "<br/>");
	
	
	$fields=array();
	$i = pg_num_fields($result_set);
	
	for ($j = 0; $j < $i; $j++) {
		array_push($fields, pg_field_name($result_set, $j));
	}
	
	$response=array();
	array_push($response, $fields);
	
	while ($row = pg_fetch_row($result_set))
	{	
		$response[] = $row;
	}
	echo json_encode($response,JSON_UNESCAPED_UNICODE);;
	// Free result_set
	pg_free_result($result_set);

	
	
	
?>