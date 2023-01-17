function check_layer(id) {
  // Get the checkbox
  var checkBox = document.getElementById(id);
  
  localStorage.setItem(id+"Checkbox", checkBox.checked);
  
  options = {
	zoom: 8,
	center: { lat: 23.5, lng: 121 },
  };
  
  
  var data = {
	  "type": "FeatureCollection",
	  "features": [
		]};
  // If the checkbox is checked, display the output text
  if (checkBox.checked == true){
	var layer = id;
	  $.ajax({
			type: "POST",
			url: 'php/getlayer.php',
			dataType: 'text',
			data: {	
				layer: id
			},
			success: function (response) {
				response = JSON.parse(response);
				var indexOfGeoJson = response[0].indexOf('st_asgeojson');
				response.shift();
				var featureId=0;
				response.forEach(function(each) {
					var geojson = JSON.parse(each[indexOfGeoJson]);
					var feature = {"type": "Feature", "id": id+featureId, "properties": {"title":each[indexOfGeoJson-1]}, "geometry": geojson};
					data.features.push(feature);
					featureId+=1;
				});
				
				map.data.addGeoJson(data);
				map.data.setStyle({
				  fillColor: 'green',
				});
			},
			error: function(response) {
				console.log(response);
			}
		});
	  
	  
  } else {
	  map.data.forEach(function(feature){
		if(feature.getId().includes(id)){
			map.data.remove(feature);
		}
	  });
	  
  }
}





