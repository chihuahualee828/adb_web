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
			}else{
				window.alert("no result");
			}
		},
		error: function(response) {
			console.log(response);
		}
	});
}

function search(response){
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


var markersArray = [];
function draw_points(response, icon){
		
	/*
	var options = {
		zoom:10
	}
	*/
	
	
	var bounds = new google.maps.LatLngBounds();
	const icons = {
	  dollar: {
		url: "img/dollar_marker.png",
		scaledSize: new google.maps.Size(32, 32),
	  },
	};
	
	for (var i = 0; i < markersArray.length; i++ ) {
	  markersArray[i].setMap(null);
	}
	markersArray.length = 0;
	
	var col = response[0]
	latIndex = response[0].indexOf('lat');
	longIndex = response[0].indexOf('long');
	for (j = 1; j < response.length; j++){
		var each = response[j];
		const pos = {
			lat: parseFloat(each[latIndex]),
			lng: parseFloat(each[longIndex])
		};
		
		const infowindow = new google.maps.InfoWindow({
			content : each.map(function (val, index) { return  "<p>" + col[index] + ": " + val}).join(""),
			position:pos,
			pixelOffset: new google.maps.Size(0, -40),

		});
		
		var marker = new google.maps.Marker({
			position: pos,
			icon: icons[icon],
			map,
		});
		markersArray.push(marker);
		marker.setPosition(pos);
		
		bounds.extend(pos);
		marker.addListener("click", () => {
			infowindow.open({
			  anchor: marker,
			  map,
			  shouldFocus: false,
			},pos);
		});
		
	}
	
	map.setCenter(bounds.getCenter());
	map.fitBounds(bounds);
	if (map.getZoom() > 12){
		map.setZoom(12); 
	}
}


function generateDataTable(response) {
	
	var array = response;
	
	var colName = array[0];
	var columnKeys = [];
	
	array[0].forEach(function(col) {
		columnKeys.push({title: col});
	});
	
	array.shift();
	
	$('#dataTable').dataTable().fnDestroy();
	$('#dataTable').empty();
	
	
	
	var dataTable = $('#dataTable').DataTable({
		
		destroy: true,
		data: array,
		columns: columnKeys,
	});
	document.getElementById('dataTableDiv').getElementsByTagName('thead')[0].style.display='';
	
	
}