<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>COVID-19 Timeline</title>
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.8.0/css/bulma.min.css">
		<script defer src="https://use.fontawesome.com/releases/v5.3.1/js/all.js"></script>
		<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
		<script>
			;(function() {
				var hasDropDown = false;
				function drawChart() {
					var rows = [];
					fetch('https://pomber.github.io/covid19/timeseries.json')
					.then(response => response.json())
					.then(data => {
						if (!hasDropDown) {
							var country = document.getElementById('country');
							for (x in data) {
								var option = document.createElement('option');
								option.value = x;
								option.innerText = x;
								if (x == "US") {
									option.selected = true;
								}
								country.append(option);
							}
							hasDropDown = true;
						}
						var cu = document.getElementById('country').value;
						for (var i=0;i<data[cu].length;i++) {
							const {date, confirmed, recovered, deaths} = data[cu][i];
							rows.push([new Date(date), confirmed, deaths, recovered]);
						}
						var options = {
							title: 'COVID-19 - ' + cu + ' Cases',
							legend: { position: 'bottom' },
							height: 800
						};
						var chart = new google.visualization.ColumnChart(document.getElementById('chart'));
						var data = new google.visualization.DataTable();
						data.addColumn('date', 'Date');
						data.addColumn('number', 'Confirmed');
						data.addColumn('number', 'Deaths');
						data.addColumn('number', 'Recovered');
						data.addRows(rows);
						chart.draw(data, options);
					});
				}
				document.addEventListener('DOMContentLoaded', function() {
					google.charts.load( 'current', { packages: [ 'corechart' ] } );
					google.charts.setOnLoadCallback( drawChart );
					document.getElementById('country').addEventListener('change', drawChart);
				});
			})();
		</script>
	</head>
	<body>
		<section class="section">
			<div class="container is-fluid">
				<div class="columns is-centered">
					<div class="column is-half">
						<div class="select is-large">
							<select id="country" name="country">
							</select>
						</div>
					</div>
				</div>
				<div id="chart"></div>
			</div>
		</section>
	</body>
</html>
