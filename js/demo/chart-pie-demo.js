// Set new default font family and font color to mimic Bootstrap's default styling
Chart.defaults.global.defaultFontFamily = 'Nunito', '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
Chart.defaults.global.defaultFontColor = '#858796';

// Draw product pie chart (parameter = county/dist./season)
function draw_pie_chart(searchText, searchhBy, parameter){

	var id = "myPieChart"
	var county = "";
	if(parameter == "district"){
		id = "myPieChart2";
		county = searchText.split("/")[1];
		searchText = searchText.split("/")[0];
	}else if (parameter =="season"){
		id = "myPieChart3";
	}
	console.log(searchText,searchhBy, parameter);
	
	$.ajax({
		type: "POST",
		url: 'php/chart_query.php',
		dataType: 'text',
		data: {	
			searchText: searchText,
			county: county,
			searchhBy: searchhBy,
			parameter: parameter
		},
		success: function (response) {
			console.log(response); //query results 2d array
			response = JSON.parse(response);
			response.shift();
	
			var labels = []
			var data = []
			var colors = [];
			var hoverColors = [];
			response.forEach(function(each) {
				labels.push(each[0]);
				data.push(each[1]);
				colors.push(dynamicColors());
				
			});
			
			var ctx = document.getElementById(id);
			var myPieChart = new Chart(ctx, {
			type: 'pie',
			data: {
				labels: labels,
				datasets: [{
				  data: data,
				  backgroundColor: colors,
				  hoverBackgroundColor: colors,
				  hoverBorderColor: "rgba(234, 236, 244, 1)",
				}],
			  },
			options: {
				maintainAspectRatio: false,
				tooltips: {
				  backgroundColor: "rgb(255,255,255)",
				  bodyFontColor: "#858796",
				  borderColor: '#dddfeb',
				  borderWidth: 1,
				  xPadding: 10,
				  yPadding: 10,
				  displayColors: false,
				  caretPadding: 10,
				},
				legend: {
				  display: true
				},
				cutoutPercentage: 0,
				}
			});
			//myPieChart.destroy();
			//removeData(myPieChart);
			//addData(myPieChart, labels, data, colors);
			//console.log(myPieChart.data.datasets[0].data);
			/**
			myPieChart = new Chart(ctx, {
			  type: 'pie',
			  data: {
				labels: labels,
				datasets: [{
				  data: data,
				  backgroundColor: colors,
				  hoverBackgroundColor: colors,
				  hoverBorderColor: "rgba(234, 236, 244, 1)",
				}],
			  },
			  options: {
				maintainAspectRatio: false,
				tooltips: {
				  backgroundColor: "rgb(255,255,255)",
				  bodyFontColor: "#858796",
				  borderColor: '#dddfeb',
				  borderWidth: 1,
				  xPadding: 10,
				  yPadding: 10,
				  displayColors: false,
				  caretPadding: 10,
				},
				legend: {
				  display: true
				},
				cutoutPercentage: 0,
			  },
			});
			**/
		},
		error: function(response) {
			console.log(response);
		}
	});
	
}

function dynamicColors(){
	var r = Math.floor(Math.random() * 255);
	var g = Math.floor(Math.random() * 255);
	var b = Math.floor(Math.random() * 255);
	return "rgb(" + r + "," + g + "," + b + ")";
 };


function addData(chart, label, data, colors) {
    chart.data.labels=label;
	chart.data.datasets[0].data = data;
	chart.data.datasets[0].backgroundColor = colors;
	chart.data.datasets[0].hoverBorderColor = "rgba(234, 236, 244, 1)";
    chart.update();
	
};

function removeData(chart) {
    chart.data.labels.pop();
    chart.data.datasets[0].data=[];
    chart.update();
};
 