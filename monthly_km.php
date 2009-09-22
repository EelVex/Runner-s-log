<?php
if (isset($_GET['graph'])) $graph = $_GET['graph'];
setlocale(LC_ALL,'el_GR.ISO8859-7');
include("/usr/local/share/jpgraph/jpgraph.php");
include("/usr/local/share/jpgraph/jpgraph_bar.php");
include("/usr/local/share/jpgraph/jpgraph_line.php");
include("/usr/local/share/jpgraph/jpgraph_date.php");

include_once("read.php");

foreach($runs as $run) {
	$m = mktime(0,0,0,strftime("%m",$run['datetime_stamp']),27,strftime("%Y",$run['datetime_stamp']));

	if (isset($monthly_km[$m])) $monthly_km[$m] += $run['distance'];
	else $monthly_km[$m] = $run['distance'];

	if (isset($monthly_pace[$m])) $monthly_pace[$m] += $run['minutes_per_km'];
	else  $monthly_pace[$m] = $run['minutes_per_km'];

	if (isset($monthly_vo[$m])) $monthly_vo[$m] += $run['vo'];
	else $monthly_vo[$m] = $run['vo'];

	if (isset($monthly_f[$m])) $monthly_f[$m]++;
	else $monthly_f[$m] = 1;
}
foreach ($monthly_f as $mon=>$f) $data_f[] = $f;
$i=0;
foreach ($monthly_km as $mon=>$km) {
	$data_km[] = $km;
	$data_kf[] = $km/$data_f[$i++];
	$dates_km[] = strftime("%b\n%y",$mon);
}
$i=0;
foreach ($monthly_vo as $mon=>$vo) {
	$data_vo[] = $vo/$data_f[$i++];
}

		// Create the basic graph
		$graph = new Graph(800,500,'auto');	
		$graph->SetScale("textlin");
		$graph->SetY2Scale("lin");
		$graph->img->SetMargin(40,80,30,60);

		// Adjust the position of the legend box
		$graph->legend->Pos(0.02,0.15);

		// Adjust the color for theshadow of the legend
		$graph->legend->SetShadow('darkgray@0.5');
		$graph->legend->SetFillColor('lightblue@0.3');

		// Set axis titles and fonts
		$graph->footer->right->Set("Σύνολο: $total_run km");
		$graph->footer->right->SetFont(FF_TIMES,FS_BOLD);
		$graph->footer->right->SetColor('black');

		// Set axis titles and fonts
		$graph->xaxis->SetFont(FF_TIMES,FS_NORMAL);
		$graph->xaxis->SetColor('blue');
		//$graph->xaxis->scale->SetDateFormat("M");

		$graph->yaxis->SetFont(FF_FONT1,FS_BOLD);
		$graph->yaxis->SetColor('blue');

		//$graph->y2axis->SetTickSide(SIDE_RIGHT);
		//$graph->y2axis->SetColor('black','blue');
		//$graph->y2axis->SetLabelFormat('%4.2f%%');

		$graph->ygrid->Show(true);
		$graph->xgrid->Show(true);

		$graph->title->Set('Στατιστικά ανά Μήνα');
		$graph->title->SetMargin(3);
		$graph->title->SetFont(FF_TIMES,FS_BOLD,12);

		$graph->xaxis->SetTickLabels($dates_km);

		$mon_km = new BarPlot($data_km);
		$mon_f  = new BarPlot($data_f);
		$mon_kf  = new BarPlot($data_kf);
		$mon_vo = new LinePlot($data_vo);

		$mon_km->SetFillColor('orange@0.4');
		$mon_f->SetFillColor('brown@0.4');

		$mon_km->SetLegend('Km');
		$mon_f->SetLegend('runs');
		$mon_kf->SetLegend('Km/run');

		$mon_vo->SetColor('blue@0.0');
		$mon_vo->mark->SetType(MARK_FILLEDCIRCLE);
		$mon_vo->mark->SetFillColor("green");
		$mon_vo->mark->SetWidth(2);
		$mon_vo->SetLegend('VO2');
		$mon_vo->value->show();

		//$mon_km->mark->SetType(MARK_FILLEDCIRCLE);
		//$mon_km->mark->SetFillColor("yellow");
		//$mon_km->mark->SetWidth(2);

		$gbarplot = new GroupBarPlot(array($mon_km,$mon_f,$mon_kf));
		$gbarplot->SetWidth(0.6);

		$graph->Add($gbarplot);
		$graph->AddY2($mon_vo);
		//$graph->Add($mon_km);
		//$graph->Add($mon_f);

		$graph->Stroke();
?>
