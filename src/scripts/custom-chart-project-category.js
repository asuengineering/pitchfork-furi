// Init script for bar chart featuring the active symposium project count.
// See templates/blocks/home-project-categories.php
// Block markup produces var barsArray, used once in the initialization script below.


// Google Charts info is not jQuery, so no doc ready needed.
google.charts.load('current', { packages: ['corechart'] });
google.charts.setOnLoadCallback(drawBarChart);

function drawBarChart() {
	var data = [
		[
			'Research Topic',
			'Project Count',
			{ role: 'style' },
			{ role: 'annotation' },
		],
	];

	barsArray.forEach((arr) => data.push(arr));

	var graphData = google.visualization.arrayToDataTable(data);

	var options = {
		backgroundColor: '#fffff',
		height: 350,
		chartArea: { width: '90%', height: '90%' },
		legend: { position: 'none' },
		bar: { groupWidth: '75%' },
		vAxis: {
			fomat: 'short',
		},
	};

	var chart = new google.visualization.ColumnChart(
		document.getElementById('subject-chart')
	);

	chart.draw(graphData, options);
}
