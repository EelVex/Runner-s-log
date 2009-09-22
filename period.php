Περίοδοι: <? echo $num_periods; ?>
<?
$i = 0;
foreach ($periods as $period) {
	// Bgale ton teleytaio agwna
	$period = array_reverse($period);
	if ($period[0]['type_of_run'] == 'R') {
		$last_race = array_shift($period);
	} else {
		unset($last_race);
	}
	$period = array_reverse($period);


	echo "<div class='period'>";
	$i++;
	echo "<h3>".stamp_format($period[0]['datetime_stamp']).' εως '.stamp_format($period[sizeof($period)-1]['datetime_stamp'])."</h3>";
	if (isset($last_race)) {
		echo "<h4>Στόχος: ".$last_race['comments'].' ('.stamp_format($last_race['datetime_stamp']).") </h4>";
	}

	$total_km = 0; $n = 0; $day = 0;
	$total_running_time = 0; $mean_pace = 0; $mean_vo = 0;
	$total_days = date("z", $period[sizeof($period)-1]['datetime_stamp']) - date("z", $period[0]['datetime_stamp'])+1;
	$prev_day = date("z",$period[1]['datetime_stamp']);
	echo "<div class='timeline'>";
	foreach ($period as $run) {
		$n++;
		$day++;
		$total_km += $run['distance'];
		$total_running_time += $run['time_total'];
		$mean_pace += $run['pace'];
		$mean_vo += $run['vo'];
		for ($i=0;$i<date("z", $run['datetime_stamp']) - $prev_day -1;$i++) {
			echo "<div>&nbsp;<br />&nbsp;<span style='font-size:8px;color: #777'>$day</span>".week_stats($day, $period)."</div>";
			$day++;
		}
		$prev_day = date("z", $run['datetime_stamp']);
		echo '<div '.style_type($run['type_of_run']).'>'.$run['type_of_run'].'<br />'.sprintf("%04.1f",$run['distance'])."<span>$n</span>".week_stats($day, $period)."</div>";
	}
	$mean_pace /= $n;
	$mean_vo /= $n;
	echo "</div>";
	echo "<p class='plain' style='clear: both;'>Στατιστικά συνόλου:<br/> <b>".sprintf("%4.2f",$total_km)."km</b>, $total_days μέρες, ".sizeof($period)." προπονήσεις
		".sprintf("%4.2f",$total_km/sizeof($period))." km/προπόνηση, <b>".sprintf("%4.2f", 7*$total_km/$total_days)." km/βδομάδα</b>,
			<b>".sprintf("%1.1f", 7*sizeof($period)/$total_days)." προπονήσεις/βδομάδα</b>
		</p><p class='plain'>Μεσος ρυθμος: ".pace_format($mean_pace)."  min/km, ".sprintf("%4.2f",$mean_vo)." vo2 </p>";

	/*
	unset($data_dates, $data);
	foreach ($period as $run) {
		$data_dates[] = $run['datetime_stamp'];
		$data[] = $run['distance'];
	}
	$title = 'Χιλιόμετρα';
	$footer = 'foot';
	$file = 'img/period'.$i.'.png';
	include('simple_graph.php');
	//echo "<div><img src='images.php?graph=pace' /></div>";
	echo "<div><img src='$file' /></div>";
	 */

	echo "</div>";
}
?>

<div>
S = short run, M = medium run, L = long run, I = interval, R = race
</div>
