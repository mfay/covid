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
				var data = null;
				function getData() {
					fetch('https://pomber.github.io/covid19/timeseries.json')
					.then(response => response.json())
					.then(jsonData => {
						data = jsonData;
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
						google.charts.load( 'current', { packages: [ 'corechart' ] } );
						google.charts.setOnLoadCallback( drawChart );
					});
				}
				function drawChart() {
					var rows = [];
					var cu = document.getElementById('country').value;
					var daily = document.getElementById('total').value === '1';
					var lastConfirmed = 0;
					var lastDeaths = 0;
					var lastRecovered = 0;
					for (var i=0;i<data[cu].length;i++) {
						const {date, confirmed, recovered, deaths} = data[cu][i];
						var totalConfirmed = (daily) ? confirmed - lastConfirmed : confirmed;
						var totalDeaths = (daily) ? deaths - lastDeaths : deaths;
						var totalRecovered = (daily) ? recovered - lastRecovered : recovered;
						rows.push([new Date(date), totalConfirmed, totalDeaths]);
						lastConfirmed = confirmed;
						lastDeaths = deaths;
						lastRecovered = recovered;
					}
					var options = {
						title: 'COVID-19 - ' + cu + ' Cases',
						legend: { position: 'bottom' },
						height: 800
					};
					var chart = new google.visualization.ColumnChart(document.getElementById('chart'));
					var table = new google.visualization.DataTable();
					table.addColumn('date', 'Date');
					table.addColumn('number', 'Confirmed');
					table.addColumn('number', 'Deaths');
					// table.addColumn('number', 'Recovered');
					table.addRows(rows);
					chart.draw(table, options);
				}
				document.addEventListener('DOMContentLoaded', function() {
					getData();
					document.getElementById('country').addEventListener('change', drawChart);
					document.getElementById('total').addEventListener('change', drawChart);
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
						<div class="select is-large">
							<select id="total" name="total">
								<option value="0">Show Cumulative Totals</option>
								<option value="1">Show Daily Totals</option>
							</select>
						</div>
					</div>
				</div>
				<div id="chart"></div>
			</div>
		</section>
	</body>
</html>
