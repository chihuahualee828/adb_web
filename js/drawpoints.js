
var markersArray = [];
var infoWindows = [];
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
			content : each.map(function (val, index) { 
				if(col[index].includes("id") | col[index]=="product_name" || col[index]=="arrival_address_normalized" ){
					return  "<p>" + col[index] + ": " + val + "<button class='btn btn-success' style='margin-left:10px;' onclick='small_search(\""+ val +"\")'><i class='fas fa-search fa-sm' style='margin:-5px'></i></button>"
				}else{
					return  "<p>" + col[index] + ": " + val
				}
			}).join(""),
			position:pos,
			pixelOffset: new google.maps.Size(0, -40),

		});
		
		infoWindows.push(infowindow); 
		
		var marker = new google.maps.Marker({
			position: pos,
			icon: icons[icon],
			map,
		});
		markersArray.push(marker);
		marker.setPosition(pos);
		
		bounds.extend(pos);
		marker.addListener("click", () => {
			for (var i=0;i<infoWindows.length;i++) {
				infoWindows[i].close();
			}
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
	
	
	var colName = response[0];
	var columnKeys = [];
	
	response[0].forEach(function(col) {
		columnKeys.push({title: col});
	});

	
	$('#dataTable').dataTable().fnDestroy();
	$('#dataTable').empty();
	
	
	
	var dataTable = $('#dataTable').DataTable({
		
		destroy: true,
		data: response.slice(1),
		columns: columnKeys,
	});
	document.getElementById('dataTableDiv').getElementsByTagName('thead')[0].style.display='';
	
	
}