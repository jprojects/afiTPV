<?php
/**
 * @version     1.0.0 Afi Framework $
 * @package     Afi Framework
 * @copyright   Copyright © 2014 - All rights reserved.
 * @license	    GNU/GPL
 * @author	    kim
 * @author mail kim@afi.cat
 * @website	    http://www.afi.cat
 *
*/

defined('_Afi') or die ('restricted access');

if(!$user->getAuth()) {
    $app->redirect($config->site);
}

$model 		= $app->getModel('report');
?>

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

<script type="text/javascript">
	google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(drawChart);

	function drawChart() {

		var data = google.visualization.arrayToDataTable([
			<?= $model->getIssuesByYear() ?>
			]);

        var options = {
          title: 'Incidències/Any (fins a data d\'avui)',
          
          legend: { position: 'bottom' }
        };

		var chart2 = document.getElementById('chart2');
        var chart = new google.visualization.LineChart(chart2);

        chart.draw(data, options);
	}
</script>

<div class="container-fluid">      	
	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="grid support-content">
					<div class="grid-body">

						<h2>Report</h2>

						<hr>
						
					</div>
				</div>
			</div>

			<div class="col-md-6">			
				<div class="grid support">
					<div class="grid-body" style="padding-top:40px;">					
						<div style="width:100%; min-height:400px; height:auto;">
							<canvas id="chart1"></canvas>
						</div>				
					</div>
				</div>				
			</div>
			
			<div class="col-md-6">
				<div class="grid support">
					<div class="grid-body" style="padding-top:40px;">				
						<div style="width:100%; min-height:400px; height:auto;" id="chart2"></div>					
					</div>
				</div>
			</div>

			<div class="col-md-6">			
				<div class="grid support">
					<div class="grid-body" style="padding-top:40px;">			
						<div style="width:100%; min-height:400px; height:auto;" id="chart3">
						</div>			
					</div>
				</div>
			</div>

			<div class="col-md-6">			
				<div class="grid support">
					<div class="grid-body" style="padding-top:40px;">					
						<div style="width:100%; min-height:400px; height:auto;" id="chart4">
						</div>						
					</div>
				</div>
			</div>		
	</section>
</div>
