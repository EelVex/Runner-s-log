<?php
include_once('functions.php');
$fp = fopen('/home/eelvex/Itnogs/files/running','r');
$dump = fgets($fp); $dump = fgets($fp); //Ignore first too lines
$i = 0; $total_run = 0; $tmp_best = 1000; $total_time = 0; $tmp_best_km = 0;
$sum_vo = 0;
$mean_km_14 = 0; $mean_pace_14 = 0; $mean_vo_14 = 0; $freq_14 = 0;
$prin14meres = mktime (0,0,0, date("m"), date("d")-14, date("Y"));
while (!feof($fp)) {
	$tmp = fgets($fp);
	if ($tmp) {
		$arr = explode("\t", $tmp);
		$arr[] = ""; /* UGLY HACK */ // GIa na eimaste sigouroi oti yparxei to $arr[5]
		$runs[$i]["date_run"] = $arr[0];
		$runs[$i]["time_run"] = $arr[1];
			list($min,$sec) = explode(":",$arr[2]);
		$runs[$i]["time_total"] = $min*60+$sec;
		$runs[$i]["laps_total"] = $arr[3];
		$runs[$i]["type_of_run"] = trim($arr[4]);
		$runs[$i]["comments"] = trim($arr[5]);

		$runs[$i]["distance"] = $runs[$i]["laps_total"]*.45;
		$runs[$i]["minutes_per_lap"] = $runs[$i]["time_total"]/$runs[$i]["laps_total"];
		$runs[$i]["minutes_per_km"] = $runs[$i]["time_total"]/$runs[$i]["laps_total"]/.45/60;
		$runs[$i]['pace'] = $runs[$i]['minutes_per_km'];

		list($min,$hour) = explode(":",$runs[$i]["time_run"]);
		list($day,$month,$year) = explode(".",$runs[$i]["date_run"]);
		$runs[$i]["datetime_stamp"] = mktime($min,$hour,0,$month,$day,$year);

		$runs[$i]["time_hours"] = intval($runs[$i]["time_total"]/3600);
		$runs[$i]["time_mins"] = intval(($runs[$i]["time_total"]-$runs[$i]["time_hours"]*3600)/60);
		$runs[$i]["time_secs"] = intval(($runs[$i]["time_total"]-$runs[$i]["time_hours"]*3600 - $runs[$i]["time_mins"]*60));

		$vo = daniels(1000*$runs[$i]['distance']/$runs[$i]['time_total']*60.0, $runs[$i]['time_total']/60.0);
		$sum_vo += $vo;
		$runs[$i]['vo'] = $vo;

		if ($tmp_best_km<$runs[$i]["distance"] 
			//&& $runs[$i]['type_of_run'] != 'R'
		) 
		{
			$best_km = $i;
			$tmp_best_km = $runs[$i]["distance"];
		}

		if ($tmp_best>$runs[$i]["minutes_per_km"]) {
			$best_pace = $i;
			$tmp_best = $runs[$i]["minutes_per_km"];
		}

		$total_run += $runs[$i]["distance"];
		$total_time += $runs[$i]["time_total"];

		if ($runs[$i]['datetime_stamp'] > $prin14meres) {
			$mean_km_14 += $runs[$i]['distance'];
			$mean_pace_14 += $runs[$i]['time_total'];
			$mean_vo_14 += $runs[$i]['vo'];
			$freq_14++;
		}
		if ($runs[$i]['type_of_run'] == 'R') {
			$races[] = $runs[$i];
		}
		$Yw = date('Y', $runs[$i]['datetime_stamp']);
		$Ww = date('W', $runs[$i]['datetime_stamp']);
		if (isset($week[$Yw][$Ww])) $week[$Yw][$Ww] += $runs[$i]['distance'];
		else $week[$Yw][$Ww] = $runs[$i]['distance'];

		$i++;
	}
}

$total_diarkeia = (-$runs[0]['datetime_stamp'] + $runs[$i-1]['datetime_stamp'])/3600/24;

$total_run = sprintf("%4.1f",$total_run);
$mean_pace = sprintf("%4.2f",$total_time/$total_run/60);
$mean_vo = sprintf("%4.2f",$sum_vo/sizeof($runs));
$mean_km = sprintf("%4.2f",$total_run/sizeof($runs));
$mean_total_km = $total_run/$total_diarkeia;

$mean_total_km_14 = sprintf("%4.2f",$mean_km_14);
$mean_pace_14 = sprintf("%4.2f", $mean_pace_14/$mean_km_14/60);
$mean_km_14 = sprintf("%4.2f",$mean_km_14/$freq_14);
$mean_vo_14 = sprintf("%4.2f",$mean_vo_14/$freq_14);

$record_days_km = 0; $record_days_vo = 0; $record_days_pc = 0;
/*
for ($i=sizeof($runs)-1;$i>=0;$i--) if ($runs[$i]['distance'] > $runs[sizeof($runs)-1]['distance']) break; else $record_days_km++;
for ($i=sizeof($runs)-1;$i>=0;$i--) if ($runs[$i]['minutes_per_km'] < $runs[sizeof($runs)-1]['minutes_per_km']) break; else $record_days_pc++;
for ($i=sizeof($runs)-1;$i>=0;$i--) if ($runs[$i]['vo'] > $runs[sizeof($runs)-1]['vo']) break; else $record_days_vo++;
 */

for ($i=7;$i<sizeof($runs);$i++) {
	$tmp_km = 0; $tmp_pc = 0; $tmp_vo = 0;
	for ($j=$i-1; $j>=0; $j--)  if ($runs[$j]['distance'] >= $runs[$i]['distance']) break; else $tmp_km++;
	for ($j=$i-1; $j>=0; $j--)  if ($runs[$j]['minutes_per_km'] <= $runs[$i]['minutes_per_km']) break; else $tmp_pc++;
	for ($j=$i-1; $j>=0; $j--)  if ($runs[$j]['vo'] >= $runs[$i]['vo']) break; else $tmp_vo++;
	$runs[$i]['record_days_km'] = $tmp_km;
	$runs[$i]['record_days_pc'] = $tmp_pc;
	$runs[$i]['record_days_vo'] = $tmp_vo;
}

$record_days_km = $runs[sizeof($runs)-1]['record_days_km'];
$record_days_pc = $runs[sizeof($runs)-1]['record_days_pc'];
$record_days_vo = $runs[sizeof($runs)-1]['record_days_vo'];

fclose($fp);

$fp = fopen('/home/eelvex/Itnogs/files/wei','r');
$dump = fgets($fp); $dump = fgets($fp); //Ignore first too lines
while (!feof($fp)) {
	$tmp = fgets($fp);
	if ($tmp) {
		$arr = explode("\t", $tmp);
		$weis[$i]["date_wei"] = $arr[0];
		list($day,$month,$year) = explode(".",$weis[$i]["date_wei"]);
		$weis[$i]["datetime_stamp"] = mktime(0,14,0,$month,$day,$year);
		$wei_stamp[mktime(0,0,0,$month,$day,$year)] = 1;
		$i++;
	}
}
fclose($fp);

$fp = fopen('/home/eelvex/Itnogs/files/swim','r');
$dump = fgets($fp); $dump = fgets($fp); //Ignore first too lines
while (!feof($fp)) {
	$tmp = fgets($fp);
	if ($tmp) {
		$arr = explode("\t", $tmp);
		$swims[$i]["date_swim"] = $arr[0];
		$swims[$i]['time'] = $arr[2];
		list($day,$month,$year) = explode(".",$swims[$i]["date_swim"]);
		$swims[$i]["datetime_stamp"] = mktime(0,14,0,$month,$day,$year);
		$swim_stamp[mktime(0,0,0,$month,$day,$year)] = 1;
		$i++;
	}
}
fclose($fp);

$fp = fopen('/home/eelvex/Itnogs/files/misc','r');
$dump = fgets($fp); $dump = fgets($fp); //Ignore first too lines
while (!feof($fp)) {
	$tmp = fgets($fp);
	if ($tmp) {
		$arr = explode("\t", $tmp);
		$arr[] = ""; /* UGLY HACK */ // GIa na eimaste sigouroi oti yparxei to $arr[5]
		$miscs[$i]['date'] = $arr[0];
		$miscs[$i]['time'] = $arr[1];
		$miscs[$i]['what'] = $arr[2];
		$miscs[$i]['comments'] = trim($arr[3]);
		list($day,$month,$year) = explode(".",$miscs[$i]['date']);
		$miscs[$i]["datetime_stamp"] = mktime(0,14,0,$month,$day,$year);
		$misc_stamp[mktime(0,0,0,$month,$day,$year)] = 1;
		$i++;
	}
}
fclose($fp);

?>
