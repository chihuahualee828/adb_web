<?php

	
	require_once('db_connect.php');
	

	// POST Data
	$searchText = $_POST['searchText'];
	
	unset($result_set);
	$query="";
	if(is_numeric($searchText)){
		$query = "select rs_id, od.product_id, product_name,arrival_address_normalized, od.lat, od.long from order_combined_pd as od
						join product_list_combined as pdw on od.product_id = pdw.product_id
					where od.product_id = ".$searchText;
					
		
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
		
		$query = "select rg_id, rs_id, od.product_id, product_name,arrival_address_normalized, od.lat, od.long from order_combined_pd as od
							join product_list_combined as pdw on od.product_id = pdw.product_id
						where od.rg_id = ".$searchText;
		
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
	
	}else{
		
		$query = "select customer_id, rs_id, od.product_id, product_name,arrival_address_normalized, od.lat, od.long from order_combined_pd as od
						join product_list_combined as pdw on od.product_id = pdw.product_id
					where strpos( product_name, '".$searchText ."') > 0"; 
	
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
		
		
		
		
		$query = "select customer_id, rs_id, od.product_id, product_name,arrival_address_normalized, od.lat, od.long from order_combined_pd as od
						join product_list_combined as pdw on od.product_id = pdw.product_id
					where od.customer_id = '".$searchText ."';"; 
	
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
							
		$query = "select rs_id, od.product_id, product_name,arrival_address_normalized, od.lat, od.long from order_combined_pd as od
							join product_list_combined as pdw on od.product_id = pdw.product_id
						where od.rs_id = '".$searchText ."'";
		
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
						
		$query = "select rm_id, rs_id, od.product_id, product_name,arrival_address_normalized, od.lat, od.long from order_combined_pd as od
							join product_list_combined as pdw on od.product_id = pdw.product_id
						where od.rm_id = '".$searchText ."'";
						
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