<script type='text/javascript' src='flot/jquery.flot.js'></script>
	<div id='graph'> </div>
	<div id='summary'></div>

<?php

foreach($runs as $run) {
	$cumdist += $run['distance'];
	$data_distance .= '['.strval(1000*intval($run['datetime_stamp'])).', '.$run['distance'].'],';
	$data_distance_total .= '['.strval(1000*intval($run['datetime_stamp'])).', '.$cumdist.'],';

	$dates .= "'".date('d-m-Y', $run['datetime_stamp'])."',";
	$distances .= "'".distance_format($run['distance'])."',";
	$times .= "'".time_format($run['time_total']/60)."',";
	$paces .= "'".pace_format($run['pace'])."',";
	$vo2s .= "'".distance_format($run['vo'])."',";
}

$data_distance = "[$data_distance]";
$data_distance_total = "[$data_distance_total]";

// Remove trailing ','s
$dates = substr($dates, 0, strlen($dates)-1);
$distances = substr($distances, 0, strlen($distances)-1);
$times = substr($times, 0, strlen($times)-1);
$paces = substr($paces, 0, strlen($paces)-1);
$vo2s = substr($vo2s, 0, strlen($vo2s)-1);

?>



	<script id='source' type='text/javascript' language='javascript'>
	$(function () {
		var ddistance = <?php echo $data_distance; ?>;
		var ddistance_total = <?php echo $data_distance_total; ?>;
		var dates = new Array(<?php echo $dates; ?>);
		var distances = new Array(<?php echo $distances; ?>);
		var times = new Array(<?php echo $times; ?>);
		var paces = new Array(<?php echo $paces; ?>);
		var vo2s = new Array(<?php echo $vo2s; ?>);

		var options = {
			xaxis: { mode: 'time', ticks: 8, timeformat: "%b/%y", monthNames: ['Ιαν', 'Φεβ', 'Μαρ', 'Απρ', 'Μαϊ', 'Ιον', 'Ιολ', 'Αυγ', 'Σεπ', 'Οκτ', 'Νοε', 'Δεκ']   },
			lines: { show: true, fill: true },
			points: { show: true },
			yaxis: {  },
			grid: {
				backgroundColor: { colors: ['#ddf', '#fff'] },
				hoverable: true
			},
		};
		var plot = $.plot($('#graph'),
			[
		{data: ddistance,  
			yaxis: 2, 
			lines: { show: false }, 
			points: { show: false}, 
			bars: {show: true, align: 'center'}
		},
		{data: ddistance_total, label: "Συνολικά km"},
		], 
		options);
		$('#graph').bind('plothover', function(event, pos, item) {
			if (item) {
				$('#summary').html("<div>Ημερομηνία: <span>"+dates[item.dataIndex]+"</span></div> \
					<div>Απόσταση: <span>"+distances[item.dataIndex]+"</span> km</div> \
					<div>Χρόνος: <span>"+times[item.dataIndex]+"</span> h:m:s</div> \
					<div>Ρυθμός: <span>"+paces[item.dataIndex]+"</span> min/km</div> \
					<div>Ένταση: <span>"+vo2s[item.dataIndex]+"</span> VO<sub>2</sub></div>"
				);
			}
		});
	});
	</script>

