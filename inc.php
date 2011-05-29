<?php
require_once("configure.inc");

$show = array('sum'=>'',
	'cal'=>'',
	'period'=>'',
	'stats'=>'',
	'guide'=>'',
	'logs'=>'',
);
if (isset($_GET['show']))  	$show[$_GET['show']] = " class='current' ";
else  			$show['sum'] = " class='current' "; 
setlocale(LC_TIME,'el_GR.ISO8859-7');
include_once('functions.php');
include_once("read.php");

$period_started = false;
$num_periods = 0;
$monthly_f = array();
foreach($runs as $run) {
	$m = strftime("%b %Y",$run['datetime_stamp']);
	if (isset($monthly_km[$m])) $monthly_km[$m] += $run['distance'];
	else $monthly_km[$m] = $run['distance'];
	if (isset($monthly_f[$m])) $monthly_f[$m]++;
	else $monthly_f[$m] = 1;
	//Find records for each category
	foreach ($record_categories as $cat) {
		if (1.10*$cat > $run['distance'] && 0.90*$cat < $run['distance']) {
			if ( !isset($record_for_category[$cat]) ||  $run['pace'] < $record_for_category[$cat]['pace'] ) {
				$record_for_category[$cat] = $run;
			}
		}
	}
	//Find periods
	if ($period_started) {
		if ($run['type_of_run'] == 'N') {
			// Period ends
			$period_started = false;
		} else {
			$periods[$num_periods][] = $run;
		}
	} else {
		if ($run['type_of_run'] != 'N' && $run['type_of_run'] != 'R') {
			$period_started = true;
			$num_periods++;
			$periods[$num_periods][] = $run;
		}
	}

}

$periods = array_reverse($periods);


if ($best_km == sizeof($runs)-1) {
	printf("<h1>New record distance: ".distance_format($runs[sizeof($runs)-1]["distance"],3)."km</h1>");
}
if ($best_pace == sizeof($runs)-1) {
	printf("<h1>New record pace: ".pace_format($runs[sizeof($runs)-1]["minutes_per_km"])." min/km</h1>");
}


$record_km = award_image('km', $record_days_km);
$record_pc = award_image('pc', $record_days_pc);
$record_vo = award_image('vo', $record_days_vo);


?>
