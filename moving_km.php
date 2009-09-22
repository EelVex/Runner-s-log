<?php
if (isset($_GET['graph'])) $graph = $_GET['graph'];
setlocale(LC_ALL,'el_GR.ISO8859-7');
include("/usr/local/share/jpgraph/jpgraph.php");
include("/usr/local/share/jpgraph/jpgraph_bar.php");
include("/usr/local/share/jpgraph/jpgraph_line.php");
include("/usr/local/share/jpgraph/jpgraph_date.php");

include_once("read.php");


/*
$i = 0;
foreach($runs as $run) {
	$m = mktime(0,0,0,strftime("%m",$run['datetime_stamp']),27,strftime("%Y",$run['datetime_stamp']));
	$this_mon = strftime("%b",$m);
	$monthly_km[$m] += $run['distance'];
	$total_time_run += $run['time_total'];
	$data_mo[] = $run['distance'];
	if ($this_mon != $prev_mon) {
		$tickPositions[] = $i;
		$dates_km[] = strftime("%b\n%y",$m);
	}
	$i++;
	$prev_mon = $this_mon;
}
 */

$period = 7;


$first_day = mktime(0,0,0,strftime("%m",$runs[0]['datetime_stamp']),strftime("%d",$runs[0]['datetime_stamp']),strftime("%Y",$runs[0]['datetime_stamp']));
$l = sizeof($runs)-1;
$last_day = mktime(0,0,0,strftime("%m",$runs[$l]['datetime_stamp']),strftime("%d",$runs[$l]['datetime_stamp']),strftime("%Y",$runs[$l]['datetime_stamp']));
$j = 0;
$c = 0;
$prev_mon = 0;
$total_time_run = 0;
for ($i=$first_day;$i<=$last_day;$i+=3600*24) {
	$m = mktime(0,0,0,strftime("%m",$runs[$j]['datetime_stamp']),strftime("%d",$runs[$j]['datetime_stamp']),strftime("%Y",$runs[$j]['datetime_stamp']));
	$mon = mktime(0,0,0,strftime("%m",$i),2,strftime("%Y",$i));
	if ($mon != $prev_mon) {
		$tickPositions[] = $c;
		$dates_km[] = strftime("%b\n%y",$mon);
	}
	//echo strftime("%d %b %y",$m);
	//echo strftime(" -  %d %b %y<br>",$i);
	if (strftime("%d %b %y",$m) == strftime("%d %b %y",$i)) {
		$last_km = $runs[$j]['distance'];
		$last_pace = $runs[$j]['pace'];
		$total_time_run += $runs[$j]['time_total'];
		$j++;
		$i -= 3600*24; //Mporei na yparxei epomeni kataxwrisi me idia imerominia
	} else {
		$last_km = 0;
	}
	/*
	$prev[7] = $prev[6];
	$prev[6] = $prev[5];
	$prev[5] = $prev[4];
	$prev[4] = $prev[3];
	$prev[3] = $prev[2];
	$prev[2] = $prev[1];
	$prev[1] = $prev[0];
	$prev[0] = $last_pace;
	 */
	$tmp = 0;
	for ($ii=1;$ii<min($period,$c);$ii++) {
		$tmp += $data_mo[$c-$ii];
	}
	$tmp += $last_pace;
	if ($c > 0) $data_mo[] = $tmp/(min($period,$c));
	else $data_mo[] = $tmp;
	/*
	$data_tmp[] = 
		$prev[0]+
		$prev[1]+
		$prev[2]+
		$prev[3]+
		$prev[4]+
		$prev[5]+
		$prev[6];
	 */
	//echo "$last_km, ".$prev[7]."<br>";
	/*
	$data_mo[] = ($data_tmp[$c] + 
		$data_tmp[$c-1] + 
		$data_tmp[$c-2] +
		$data_tmp[$c-3] +
		$data_tmp[$c-4] +
		$data_tmp[$c-5] +
		$data_tmp[$c-6] 
	)/7;
	 */
	$prev_mon = $mon;
	$c++;
}

// Create the basic graph
$graph = new Graph(800,500,'auto');	
$graph->SetScale("textlin");
$graph->img->SetMargin(40,80,30,60);

// Adjust the position of the legend box
$graph->legend->Pos(0.02,0.15);

// Adjust the color for theshadow of the legend
$graph->legend->SetShadow('darkgray@0.5');
$graph->legend->SetFillColor('lightblue@0.3');

// Set axis titles and fonts
$graph->footer->right->Set("Σύνολο χρόνος: ".time_format($total_time_run/60));
$graph->footer->right->SetFont(FF_TIMES,FS_BOLD);
$graph->footer->right->SetColor('black');

// Set axis titles and fonts
$graph->xaxis->SetFont(FF_TIMES,FS_NORMAL);
$graph->xaxis->SetColor('blue');
//$graph->xaxis->scale->SetDateFormat("M");

$graph->yaxis->SetFont(FF_FONT1,FS_BOLD);
$graph->yaxis->SetColor('blue');


$graph->ygrid->Show(true);
$graph->xgrid->Show(true);

$graph->title->Set('μέση ταχύτητα');
$graph->title->SetMargin(3);
$graph->title->SetFont(FF_TIMES,FS_BOLD,12);

//$graph->xaxis->SetTickLabels($dates_km);
$graph->xaxis->SetMajTickPositions($tickPositions,$dates_km);

$mo_km = new LinePlot($data_mo);

$mo_km->SetColor('blue@0.0');
//$mo_km->mark->SetType(MARK_FILLEDCIRCLE);
$mo_km->mark->SetFillColor("green");
$mo_km->mark->SetWidth(2);
$mo_km->SetLegend('min/km');

$graph->Add($mo_km);

$graph->Stroke();
?>
