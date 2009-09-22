<?

/*
	foreach ($period as $run) {
		$data_dates[] = $run['datetime_stamp'];
		$data[] = $run['distance'];
	}
	$title = 'Χιλιόμετρα';
	$footer = 'foot';
	$file = 'img/period'.$i.'.png';
	include('simple_graph.php');
	echo "<div><img src='$file' /></div>";
 */
?>
<div class='graph'><img src='monthly_km.php' /></div>
<div class='graph'><img src='moving_km.php' /></div>
<div class='graph'><img src='weekly_km.php' /></div>
