function submit_query(){
	if(localStorage.getItem("filters")==null){
		return null;
	}
	query = JSON.parse(localStorage.getItem("filters"));
	
	if(query[0]=="---" || query[1]=="---"){
		return null;
	}
	var icon = "dollar";
	
	if(query[0]=="Best Logistics Cetner Location"){
		icon="default";
	}
	
	$.ajax({
		type: 'POST',
		url: 'php/db_query.php',
		dataType: 'text',
		data: {	
			query: query
		},
		success: function (response) {
			console.log(response); //query results 2d array 
			//localStorage.setItem("points",response);
			response = JSON.parse(response);
			if(response!="" && response!=null && response.length>1){
				draw_points(response, icon);
				generateDataTable(response);
				if(query[0]=="Best Seller"){
					product_id = response[1][response[0].indexOf("product_id")];
					draw_pie_chart(product_id, "product_id", "county");
				}else if (query[0]=="Top Category") {
					primary_category = response[1][response[0].indexOf("primary_category")];
					draw_pie_chart(primary_category, "primary_category", "county");
				}
				
			}else{
				window.alert("no result");
			}
		},
		error: function(response) {
			console.log(response);
		}
	});
}

function search(){
	var searchText = document.getElementById("search_box").value;
	if(searchText!=""){
		$.ajax({
			type: "POST",
			url: 'php/search.php',
			dataType: 'text',
			data: {	
				searchText: searchText
			},
			success: function (response) {
				console.log(response); //query results 2d array
				response = JSON.parse(response);
				if(response!="" && response!=null && response.length>1){
					draw_points(response, "dollar");
					generateDataTable(response);
					if(response[0].indexOf("product_id")==0){
						draw_pie_chart(searchText,"product_id", "county");
					}else if(response[0].indexOf("product_name")==0){
						draw_pie_chart(searchText,"product_name", "county");
					}else if(response[0].indexOf("primary_category")==0){
						draw_pie_chart(searchText,"primary_category", "county");
					}
				}else{
					window.alert("no result");
				}
			},
			error: function(response) {
				console.log(response);
			}
		});
	}
}


function small_search(searchText){
	if(searchText && searchText!=""){
		$.ajax({
			type: "POST",
			url: 'php/search.php',
			dataType: 'text',
			data: {	
				searchText: searchText
			},
			success: function (response) {
				console.log(response); //query results 2d array
				response = JSON.parse(response);
				if(response!="" && response!=null && response.length>1){
					draw_points(response, "dollar");
					generateDataTable(response);
				}else{
					window.alert("no result");
				}
			},
			error: function(response) {
				console.log(response);
			}
		});
	}
}
