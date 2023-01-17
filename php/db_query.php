<?php

	require_once('db_connect.php');

	// POST Data
	$queryType = $_POST['query'][0];
	$county = $_POST['query'][1];
	$district = $_POST['query'][2];
	$season = $_POST['query'][3];
	
	$query="";
	unset($result_set);
	if($season!="---"){
		switch ($season) {
			case 'Q1':
			$seasonFilter = "EXTRACT(MONTH from TO_TIMESTAMP(actual_shipping_time, 'YYY-MM-DD HH24:MI:SS')) < 4 ";
			break;
			case 'Q2':
			$seasonFilter = "EXTRACT(MONTH from TO_TIMESTAMP(actual_shipping_time, 'YYY-MM-DD HH24:MI:SS')) >=4 and EXTRACT(MONTH from TO_TIMESTAMP(actual_shipping_time, 'YYY-MM-DD HH24:MI:SS')) < 7 ";
			break;
			case 'Q3':
			$seasonFilter = "EXTRACT(MONTH from TO_TIMESTAMP(actual_shipping_time, 'YYY-MM-DD HH24:MI:SS')) >=7 and EXTRACT(MONTH from TO_TIMESTAMP(actual_shipping_time, 'YYY-MM-DD HH24:MI:SS')) < 10 ";
			break;
			case 'Q4':
			$seasonFilter = "EXTRACT(MONTH from TO_TIMESTAMP(actual_shipping_time, 'YYY-MM-DD HH24:MI:SS')) >=10 and EXTRACT(MONTH from TO_TIMESTAMP(actual_shipping_time, 'YYY-MM-DD HH24:MI:SS')) <= 12 ";
			break;
		}
	}
	if( $queryType=="Best Seller" ){
		if($district=="---" and $season=="---"){
			$query = "select od.product_id, product_name,arrival_address_normalized, od.lat, od.long, count(*) from order_combined_pd as od
							join product_list_combined as pdw on od.product_id = pdw.product_id
						where od.product_id IN
						(select od.product_id from order_combined_pd as od
							where strpos(od.arrival_address_normalized, '".$county."')  > 0 
							group by od.product_id
							order by count(*) desc limit 1
						) and strpos(od.arrival_address_normalized, '".$county."') >0 
						group by od.product_id, product_name,arrival_address_normalized, od.lat, od.long";
		}else if($district=="---"){
			
			$query = "select od.product_id, product_name,arrival_address_normalized, od.lat, od.long, count(*) from order_combined_pd as od
							join product_list_combined as pdw on od.product_id = pdw.product_id
						where od.product_id IN
						(select od.product_id from order_combined_pd as od
							where strpos(od.arrival_address_normalized, '".$county. "')  > 0 and ". $seasonFilter .
							"group by od.product_id
							order by count(*) desc limit 1
						) and strpos(od.arrival_address_normalized, '".$county."') >0 and " .$seasonFilter.
						"group by od.product_id, product_name,arrival_address_normalized, od.lat, od.long";
			
		}else if($season=="---"){
			$query = "select od.product_id, product_name,arrival_address_normalized, od.lat, od.long, count(*) from order_combined_pd as od
							join product_list_combined as pdw on od.product_id = pdw.product_id
						where od.product_id IN
						(select od.product_id from order_combined_pd as od
							where strpos(od.arrival_address_normalized, '".$county."')  > 0 and strpos(od.arrival_address_normalized, '".$district."')  > 0 
							group by od.product_id
							order by count(*) desc limit 1
						) and strpos(od.arrival_address_normalized, '".$county."') >0 and strpos(od.arrival_address_normalized, '".$district."')  > 0 
						group by od.product_id, product_name,arrival_address_normalized, od.lat, od.long";
		}else{
			$query = "select od.product_id, product_name,arrival_address_normalized, od.lat, od.long, count(*) from order_combined_pd as od
							join product_list_combined as pdw on od.product_id = pdw.product_id
						where od.product_id IN
						(select od.product_id from order_combined_pd as od
							where strpos(od.arrival_address_normalized, '".$county."')  > 0 and strpos(od.arrival_address_normalized, '".$district."')  > 0 and ". $seasonFilter . 
							"group by od.product_id
							order by count(*) desc limit 1
						) and strpos(od.arrival_address_normalized, '".$county."') >0 and strpos(od.arrival_address_normalized, '".$district."')  > 0 and ". $seasonFilter .
						"group by od.product_id, product_name,arrival_address_normalized, od.lat, od.long";
			
		}
	}
	
	if( $queryType=="Top Category" ){
		if($district=="---" and $season=="---"){
			$query = "select rg_id, rm_id, rs_id, od.product_id, product_name, primary_category, arrival_address_normalized, lat, long from order_combined_pd as od, product_list_combined as pd
						where od.product_id = pd.product_id and
						pd.primary_category = 
						(SELECT primary_category from prodcut_category_rank_by_county where countyname='".$county ."') and strpos(arrival_address_normalized, '".$county ."') > 0";
		}else if($district=="---"){
			
			$query = "select rg_id, rm_id, rs_id, od.product_id, product_name, primary_category, arrival_address_normalized, lat, long from order_combined_pd as od, product_list_combined as pd
						where od.product_id = pd.product_id and
						pd.primary_category = 
						(SELECT primary_category from prodcut_category_rank_by_county_".$season ." countyname='".$county ."') and strpos(arrival_address_normalized, '".$county ."') > 0";
			
		}else if($season=="---"){
			$query = "select rg_id, rm_id, rs_id, od.product_id, product_name, primary_category, arrival_address_normalized, lat, long from order_combined_pd as od, product_list_combined as pd
						where od.product_id = pd.product_id and
						pd.primary_category = 
						(SELECT primary_category from prodcut_category_rank_by_district where countyname='".$county ."' and townname='".$district ."') and strpos(arrival_address_normalized, '".$county ."') > 0 and strpos(arrival_address_normalized, '".$district ."') > 0";
		}else{
			$query = "select rg_id, rm_id, rs_id, od.product_id, product_name, primary_category, arrival_address_normalized, lat, long from order_combined_pd as od, product_list_combined as pd
						where od.product_id = pd.product_id and
						pd.primary_category = 
						(SELECT primary_category from prodcut_category_rank_by_district_".$season ." where countyname='".$county ."' and townname='".$district ."') and strpos(arrival_address_normalized, '".$county ."') > 0 and strpos(arrival_address_normalized, '".$district ."') > 0";
			
		}
	}
	
	if( $queryType=="Best Logistics Cetner Location" ){
		if($district=="---"){
			$query = "WITH points_collection AS 
			(select ST_collect(dump_geom) as geoms from	
				(select od.geom as dump_geom
					from order_combined as od where strpos(od.arrival_address_normalized, '".$county ."') >0
				Union
				select geom
					from supplier where strpos(supplier_address_normalized, '".$county ."') >0) combined)
			SELECT
			  ST_Y(ST_GeometricMedian(geoms)) lat, ST_X(ST_GeometricMedian(geoms)) long
			FROM points_collection;";
		}else{
			$query = "WITH points_collection AS 
						(select ST_collect(dump_geom) as geoms, townname from	
							(select od.geom as dump_geom, townname
								from order_combined as od, town_moi_1111118 as district where st_within(od.geom, district.geom) 
								and district.countyname='".$county ."' and district.townname='".$district ."'
							Union
							select supplier.geom, townname
								from supplier, town_moi_1111118 as district where st_within(supplier.geom, district.geom)
								and district.countyname='".$county ."' and district.townname='".$district ."') combined
							group by townname)
						SELECT
						  ST_Y(ST_GeometricMedian(geoms)) lat, ST_X(ST_GeometricMedian(geoms)) long
						FROM points_collection group by lat, long;";
			
		}
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