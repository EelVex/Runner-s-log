<?php
$graph = $_GET['graph'];
setlocale(LC_ALL,'el_GR.ISO8859-7');
include("/usr/local/www/jpgraph-2.1.3/src/jpgraph.php");
include("/usr/local/www/jpgraph-2.1.3/src/jpgraph_bar.php");
include("/usr/local/www/jpgraph-2.1.3/src/jpgraph_line.php");
include("/usr/local/www/jpgraph-2.1.3/src/jpgraph_date.php");

include_once("read.php");

$jl = 0; $jm = 0; $js = 0; $jn = 0; $jr = 0;
$exp = 0.25;
for ($i=0;$i<sizeof($runs);$i++) {
	$data_pace[$i] = $runs[$i]['minutes_per_km'];
	//if ($i>0) $data_vo[$i] = (1-$exp)*$runs[$i-1]['vo'] + $exp*$runs[$i]['vo']; else $data_vo[$i] = $runs[$i]['vo'];
	$data_vo[$i] = $runs[$i]['vo'];
	$data_km[$i] = $data_km[$i-1] + $runs[$i]['distance'];
	$dates_pace[$i] = $runs[$i]["datetime_stamp"];
	if ($i>0) $data_pace_exp[$i] = (1-$exp)*$data_pace_exp[$i-1] + ($exp)*$runs[$i]['minutes_per_km']; else $data_pace_exp[0] = $runs[0]['minutes_per_km'];
	if ($i>0) $data_km_exp[$i] = (1-$exp)*$data_km_exp[$i-1] + ($exp)*$runs[$i]['distance']; else $data_km_exp[0] = $runs[0]['distance'];
	if ($runs[$i]['type_of_run'] == "N" || $runs[$i]['type_of_run'] == "I" ) {
		$data_pace_n[$jn] = $runs[$i]['minutes_per_km'];
		$dates_pace_n[$jn] = $runs[$i]["datetime_stamp"];
		$data_km_n[$jn] = $runs[$i]['distance'];
		$dates_km_n[$jn] = $runs[$i]["datetime_stamp"];
		$jn++;
	}
	if ($runs[$i]['type_of_run'] == "S") {
		$data_pace_s[$js] = $runs[$i]['minutes_per_km'];
		$dates_pace_s[$js] = $runs[$i]["datetime_stamp"];
		$data_km_s[$js] = $runs[$i]['distance'];
		$dates_km_s[$js] = $runs[$i]["datetime_stamp"];
		$js++;
	}
	if ($runs[$i]['type_of_run'] == "L") {
		$data_pace_l[$jl] = $runs[$i]['minutes_per_km'];
		$dates_pace_l[$jl] = $runs[$i]["datetime_stamp"];
		$data_km_l[$jl] = $runs[$i]['distance'];
		$dates_km_l[$jl] = $runs[$i]["datetime_stamp"];
		$jl++;
	}
	if ($runs[$i]['type_of_run'] == "M") {
		$data_pace_m[$jm] = $runs[$i]['minutes_per_km'];
		$dates_pace_m[$jm] = $runs[$i]["datetime_stamp"];
		$data_km_m[$jm] = $runs[$i]['distance'];
		$dates_km_m[$jm] = $runs[$i]["datetime_stamp"];
		$jm++;
	}
	if ($runs[$i]['type_of_run'] == "R") {
		$data_pace_r[$jr] = $runs[$i]['minutes_per_km'];
		$dates_pace_r[$jr] = $runs[$i]["datetime_stamp"];
		$data_km_r[$jr] = $runs[$i]['distance'];
		$dates_km_r[$jr] = $runs[$i]["datetime_stamp"];
		$jr++;
	}

}

switch ($graph) {

	case "km":
		// Create the basic graph
		$graph = new Graph(800,500,'auto');	
		$graph->SetScale("datlin");
		$graph->img->SetMargin(40,80,30,50);

		// Adjust the position of the legend box
		$graph->legend->Pos(0.02,0.15);

		// Adjust the color for theshadow of the legend
		$graph->legend->SetShadow('darkgray@0.5');
		$graph->legend->SetFillColor('lightblue@0.3');

		// Set axis titles and fonts
		$graph->footer->right->Set("Σύνολο: $total_run km");
		$graph->footer->right->SetFont(FF_GREEK,FS_BOLD);
		$graph->footer->right->SetColor('black');

		$graph->xaxis->SetFont(FF_FONT1,FS_NORMAL);
		$graph->xaxis->SetColor('blue');
		$graph->xaxis->scale->SetDateFormat("d\nM");

		$graph->yaxis->SetFont(FF_FONT1,FS_BOLD);
		$graph->yaxis->SetColor('blue');

		$graph->ygrid->Show(true);
		$graph->xgrid->Show(true);

		$graph->title->Set('Χιλιόμετρα ανά μέρα');
		$graph->title->SetMargin(3);
		$graph->title->SetFont(FF_COMIC,FS_NORMAL,12);

		$km = new LinePlot($data_km, $dates_pace);
		$km->SetFillGradient('red','yellow');
		$km->SetColor('brown@0.0');
		$km->mark->SetType(MARK_FILLEDCIRCLE);
		$km->mark->SetFillColor("blue");
		$km->mark->SetWidth(2);
		/*
		$km_long = new LinePlot($data_km_l,$dates_km_l);
		$km_med = new LinePlot($data_km_m,$dates_km_m);
		$km_short = new LinePlot($data_km_s,$dates_km_s);
		$km_norm = new LinePlot($data_km_n,$dates_km_n);
		$km_race = new LinePlot($data_km_r,$dates_km_r);

		//$km_long->SetFillGradient('red','yellow');
		//$km_norm->SetFillGradient('green','blue');
		//$km_short->SetFillGradient('blue','yellow');
		//$km_med->SetFillGradient('white','yellow');

		$km_long->SetColor('orange@0.0');
		$km_med->SetColor('darkgreen@0.0');
		$km_short->SetColor('brown@0.0');
		$km_norm->SetColor('blue');
		$km_race->SetColor('violet');

		$km_long->mark->SetType(MARK_FILLEDCIRCLE);
		$km_long->mark->SetFillColor("red");
		$km_long->mark->SetWidth(2);
		$km_long->SetLegend('Long');

		$km_med->mark->SetType(MARK_FILLEDCIRCLE);
		$km_med->mark->SetFillColor("blue");
		$km_med->mark->SetWidth(2);
		$km_med->SetLegend('Medium');

		$km_short->mark->SetType(MARK_FILLEDCIRCLE);
		$km_short->mark->SetFillColor("green");
		$km_short->mark->SetWidth(2);
		$km_short->SetLegend('Short');

		$km_norm->mark->SetType(MARK_FILLEDCIRCLE);
		$km_norm->mark->SetFillColor("yellow");
		$km_norm->mark->SetWidth(2);
		$km_norm->SetLegend('Normal');

		$km_race->mark->SetType(MARK_FILLEDCIRCLE);
		$km_race->mark->SetFillColor("brown");
		$km_race->mark->SetWidth(2);
		$km_race->SetLegend('Races');

		$graph->Add($km_long);
		$graph->Add($km_med);
		$graph->Add($km_short);
		$graph->Add($km_norm);
		$graph->Add($km_race);
		 */

		$graph->Add($km);

		$graph->Stroke();
		break;
	case "pace":
		// Create the basic graph
		$graph = new Graph(800,500,'auto');	
		$graph->SetScale("datlin");
		$graph->img->SetMargin(40,80,30,50);

		// Adjust the position of the legend box
		$graph->legend->Pos(0.02,0.15);

		// Adjust the color for theshadow of the legend
		$graph->legend->SetShadow('darkgray@0.5');
		$graph->legend->SetFillColor('lightblue@0.3');

		$graph->footer->right->Set("Μέσος ρυθμός: $mean_pace  min/km");
		$graph->footer->right->SetFont(FF_GREEK,FS_BOLD);
		$graph->footer->right->SetColor('black');

		$graph->xaxis->SetFont(FF_FONT1,FS_NORMAL);
		$graph->xaxis->SetColor('blue');
		$graph->xaxis->scale->SetDateFormat("d\nM");

		$graph->yaxis->SetFont(FF_FONT1,FS_BOLD);
		$graph->yaxis->SetColor('blue');

		$graph->ygrid->Show(true);
		$graph->xgrid->Show(true);

		$graph->title->Set('Ρυθμός ανά ημέρα');
		$graph->title->SetMargin(3);
		$graph->title->SetFont(FF_COMIC,FS_NORMAL,12);

		$pace_l = new LinePlot($data_pace_l,$dates_pace_l);
		$pace_m = new LinePlot($data_pace_m,$dates_pace_m);
		$pace_s = new LinePlot($data_pace_s,$dates_pace_s);
		$pace_n = new LinePlot($data_pace_n,$dates_pace_n);
		$pace_r = new LinePlot($data_pace_r,$dates_pace_r);
		$pace = new LinePlot($data_pace_exp, $dates_pace);

		$pace->SetColor('orange@0.0');

		$pace_l->SetColor('orange@0.0');
		$pace_m->SetColor('darkgreen@0.0');
		$pace_s->SetColor('brown@0.0');
		$pace_n->SetColor('blue@0.0');
		$pace_r->SetColor('violet');

		$pace_l->SetLegend('Long');
		$pace_m->SetLegend('Medium');
		$pace_n->SetLegend('Normal');
		$pace_s->SetLegend('Short');
		$pace_r->SetLegend('Race');

		$pace->mark->SetType(MARK_FILLEDCIRCLE);
		$pace->mark->SetFillColor("red");
		$pace->mark->SetWidth(2);

		$pace_l->mark->SetType(MARK_FILLEDCIRCLE);
		$pace_l->mark->SetFillColor("red");
		$pace_l->mark->SetWidth(2);

		$pace_m->mark->SetType(MARK_FILLEDCIRCLE);
		$pace_m->mark->SetFillColor("blue");
		$pace_m->mark->SetWidth(2);

		$pace_s->mark->SetType(MARK_FILLEDCIRCLE);
		$pace_s->mark->SetFillColor("green");
		$pace_s->mark->SetWidth(2);

		$pace_n->mark->SetType(MARK_FILLEDCIRCLE);
		$pace_n->mark->SetFillColor("yellow");
		$pace_n->mark->SetWidth(2);

		$pace_r->mark->SetType(MARK_FILLEDCIRCLE);
		$pace_r->mark->SetFillColor("brown");
		$pace_r->mark->SetWidth(2);

		$graph->Add($pace_l);
		$graph->Add($pace_m);
		$graph->Add($pace_s);
		$graph->Add($pace_n);
		$graph->Add($pace_r);
		//$graph->Add($pace);


		$graph->Stroke();
		break;
	case "mean_pace":
	case "mean_km":
		// Create the basic graph
		$graph = new Graph(800,500,'auto');	
		$graph->SetScale("datlin");
		$graph->SetY2Scale("lin");
		$graph->img->SetMargin(40,80,30,50);

		// Adjust the position of the legend box
		$graph->legend->Pos(0.02,0.15);

		// Adjust the color for theshadow of the legend
		$graph->legend->SetShadow('darkgray@0.5');
		$graph->legend->SetFillColor('lightblue@0.3');

		$graph->footer->right->Set("Μέσος ρυθμός: $mean_pace  min/km");
		$graph->footer->right->SetFont(FF_GREEK,FS_BOLD);
		$graph->footer->right->SetColor('black');

		$graph->xaxis->SetFont(FF_FONT1,FS_NORMAL);
		$graph->xaxis->SetColor('blue');
		$graph->xaxis->scale->SetDateFormat("d\nM");

		$graph->y2axis->SetFont(FF_FONT1,FS_BOLD);
		$graph->y2axis->SetColor('blue');
		$graph->yaxis->SetFont(FF_FONT1,FS_BOLD);
		$graph->yaxis->SetColor('blue');

		$graph->yaxis->title->Set("min/Km");
		$graph->y2axis->title->Set("Km");

		$graph->ygrid->Show(true);
		$graph->xgrid->Show(true);

		$graph->title->Set('Ε.Μ.Ο.');
		$graph->title->SetMargin(3);
		$graph->title->SetFont(FF_COMIC,FS_NORMAL,12);

		$pace = new LinePlot($data_pace_exp, $dates_pace);
		$km = new LinePlot($data_km_exp, $dates_pace);

		$pace->SetColor('orange@0.0');
		$km->SetColor('blue@0.0');

		$pace->mark->SetType(MARK_FILLEDCIRCLE);
		$pace->mark->SetFillColor("red");
		$pace->mark->SetWidth(2);

		$km->mark->SetType(MARK_FILLEDCIRCLE);
		$km->mark->SetFillColor("green");
		$km->mark->SetWidth(2);

		$pace->SetLegend('Pace');
		$km->SetLegend('Distance');

		//if ($_GET['graph'] == 'mean_pace') {
			$graph->Add($pace);
		//} else {
			$graph->AddY2($km);
		//}


		$graph->Stroke();
		break;
	case "vo":
		// Create the basic graph
		$graph = new Graph(800,500,'auto');	
		$graph->SetScale("datlin");
		$graph->img->SetMargin(40,80,30,50);

		// Adjust the position of the legend box
		$graph->legend->Pos(0.02,0.15);

		// Adjust the color for theshadow of the legend
		$graph->legend->SetShadow('darkgray@0.5');
		$graph->legend->SetFillColor('lightblue@0.3');

		$graph->footer->right->Set("Μέσο VO: $mean_vo  ml/kg/min");
		$graph->footer->right->SetFont(FF_GREEK,FS_BOLD);
		$graph->footer->right->SetColor('black');

		$graph->xaxis->SetFont(FF_FONT1,FS_NORMAL);
		$graph->xaxis->SetColor('blue');
		$graph->xaxis->scale->SetDateFormat("d\nM");

		$graph->yaxis->SetFont(FF_FONT1,FS_BOLD);
		$graph->yaxis->SetColor('blue');

		$graph->ygrid->Show(true);
		$graph->xgrid->Show(true);

		$graph->title->Set('VO2 calculations.');
		$graph->title->SetMargin(3);
		$graph->title->SetFont(FF_COMIC,FS_NORMAL,12);

		$vo = new LinePlot($data_vo, $dates_pace);

		$vo->SetColor('green@0.0');

		$vo->mark->SetType(MARK_FILLEDCIRCLE);
		$vo->mark->SetFillColor("blue");
		$vo->mark->SetWidth(2);

		$vo->SetLegend('VO2');

		$graph->Add($vo);


		$graph->Stroke();
		break;
}
?>
