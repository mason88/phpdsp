<html>
	<head>
		<title>PHP-DSP Examples</title>
		<!--Load the AJAX API-->
		<script type="text/javascript" src="https://www.google.com/jsapi"></script>
		<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
		<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
		<script type="text/javascript">
			google.charts.load('45', {'packages':['corechart']});
			
			function drawChart(chart_title, data_url) {
				var jsonData = $.ajax({
					url: data_url,
					dataType:"json",
					async: false
				}).responseText;

				var data = google.visualization.arrayToDataTable(JSON.parse(jsonData));
				var chart = new google.visualization.LineChart(document.getElementById('chart_div'));
				chart.draw(data, { title: chart_title, curveType: 'function', legend: { position: 'bottom' }});
			}
		</script>
		<style>
			.desc { width:600px }
		</style>
	</head>

	<body>
		<!--Div that will hold the column chart-->
		<div id="chart_div"></div>
		
		<h2>Load Example Chart:</h2>
		
		<h3 class="desc">Creating Waves</h3>
		<div class="desc">Waves are created by first creating a signal, and then calling make_wave().</div>
		<ul>
			<li><a href="#" onclick="drawChart('Sin Wave', 'data_sin.php');">Sin Wave</a></li>
			<li><a href="#" onclick="drawChart('Triangle Wave', 'data_triangle.php');">Triangle Wave</a></li>
			<li><a href="#" onclick="drawChart('Square Wave', 'data_square.php');">Square Wave</a></li>
		</ul>
		
		<h3 class="desc">Wave Operations</h3>
		<div class="desc">Some of the single wave operations you can perform are apodize, shift, scale, and normalize.
			For two waves, you can add, subtract, divide, multiply, concatenate a wave to the other.</div>
		<ul>
			<li><a href="#" onclick="drawChart('Single Wave Operations', 'data_operations.php');">Single Wave Operations</a></li>
			<li><a href="#" onclick="drawChart('Combination of 2 Waves', 'data_combined.php');">Combined Wave</a></li>
			<li><a href="#" onclick="drawChart('Concat of 2 Waves', 'data_concat.php');">Concat of two Waves</a></li>
		</ul>
		
		<h3 class="desc">Importing Data</h3>
		<div class="desc">Example process for importing outside data.</div>
		<ul>
			<li><a href="#" onclick="drawChart('Dow Jones 1/3/2016 to 6/30/2016', 'data_csv_import.php');">Import CSV file of 6 months Dow Jones data</a></li>
		</ul>
		
		<h3 class="desc">Working in Frequency Domain</h3>
		<div class="desc">From a wave, you can call make_dct() to work in the frequency domain. DFT is not supported.</div>
		<ul>		
			<li><a href="#" onclick="drawChart('DCT Spectrum of Triangle Wave', 'data_tri_dct.php');">DCT Spectrum of Triangle Wave</a></li>
			<li><a href="#" onclick="drawChart('DCT Power Spectrum of Triangle Wave', 'data_tri_dct_pow.php');">DCT Power Spectrum of Triangle Wave</a></li>
			<li><a href="#" onclick="drawChart('IDCT of Triangle Wave', 'data_tri_idct.php');">IDCT of Triangle Wave</a></li>
			<li><a href="#" onclick="drawChart('Dow Jones 1/3/2016 to 6/30/2016 - Low Pass Filtered DCT', 'data_lowpass.php');">Low Pass Filter</a></li>
			<li><a href="#" onclick="drawChart('Dow Jones 1/3/2016 to 6/30/2016 - HighPass Filtered DCT', 'data_highpass.php');">High Pass Filter</a></li>
			<li><a href="#" onclick="drawChart('Dow Jones 1/3/2016 to 6/30/2016 - Band Pass Filtered DCT', 'data_bandpass.php');">Band Pass Filter</a></li>
		</ul>
		
		<h3 class="desc">Signal Operation</h3>
		<div class="desc"></div>
		<ul>
			<li><a href="#" onclick="drawChart('Combination of 3 Signals', 'data_combined_sig.php');">Combined Signal</a></li>
		</ul>			
	</body>
</html>
