<?php

	
	require_once('db_connect.php');
	

	// POST Data

	$searchText = $_POST['searchText'];
	$searchhBy = $_POST['searchhBy'];
	$groupby = $_POST['parameter'];
	unset($result_set);
	$query="";
	if($groupby=="county"){
		
		if($searchhBy == "product_id"){
			$query = "select countyname, count(*)  from order_combined_pd as od
				join product_list_combined as pd on od.product_id = pd.product_id
				join county_moi_1090820 as county on st_within(od.geom, county.geom)
				where od.product_id = ".$searchText ." group by countyname";
		}else if($searchhBy == "primary_category"){
			$query = "select countyname, count(*)  from order_combined_pd as od
				join product_list_combined as pd on od.product_id = pd.product_id
				join county_moi_1090820 as county on st_within(od.geom, county.geom)
				where primary_category = ".$searchText ." group by countyname";
		}else if($searchhBy == "product_name"){
			$query = "select countyname, count(*)  from order_combined_pd as od
				join product_list_combined as pd on od.product_id = pd.product_id
				join county_moi_1090820 as county on st_within(od.geom, county.geom)
				where strpos(product_name, '".$searchText ."')>0 group by countyname";
		}		
		
		$result_set = pg_query($connection, $query) 
		or die("Encountered an error when executing given sql statement: ". pg_last_error(). "<br/>");
		
		if(pg_num_rows($result_set)>0){
			$response=array();
			$fields=array();
			$i = pg_num_fields($result_set);
			
			for ($j = 0; $j < $i; $j++) {
				array_push($fields, pg_field_name($result_set, $j));
			}
			
			array_push($response, $fields);
			while ($row = pg_fetch_row($result_set))
			{	
				$response[] = $row;
			}
			echo json_encode($response,JSON_UNESCAPED_UNICODE);
			// Free result_set
			pg_free_result($result_set);
			exit();
		}
	}
		
	
				

	exit();
	
	
?>