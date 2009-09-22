<?
include_once("/usr/local/www/jpgraph-2.1.3/src/jpgraph.php");
include_once("/usr/local/www/jpgraph-2.1.3/src/jpgraph_bar.php");
include_once("/usr/local/www/jpgraph-2.1.3/src/jpgraph_line.php");
include_once("/usr/local/www/jpgraph-2.1.3/src/jpgraph_date.php");

		// Create the basic graph
		$graph = new Graph(800,500,'auto');	
		$graph->SetScale("datlin");
		$graph->img->SetMargin(40,80,30,50);

		// Adjust the position of the legend box
		$graph->legend->Pos(0.02,0.15);

		// Adjust the color for theshadow of the legend
		$graph->legend->SetShadow('darkgray@0.5');
		$graph->legend->SetFillColor('lightblue@0.3');

		$graph->footer->right->Set($footer);
		$graph->footer->right->SetFont(FF_GREEK,FS_BOLD);
		$graph->footer->right->SetColor('black');

		$graph->xaxis->SetFont(FF_FONT1,FS_NORMAL);
		$graph->xaxis->SetColor('blue');
		$graph->xaxis->scale->SetDateFormat("d\nM");

		$graph->yaxis->SetFont(FF_FONT1,FS_BOLD);
		$graph->yaxis->SetColor('blue');

		$graph->ygrid->Show(true);
		$graph->xgrid->Show(true);

		$graph->title->Set($title);
		$graph->title->SetMargin(3);
		$graph->title->SetFont(FF_COMIC,FS_NORMAL,12);

		$simple = new LinePlot($data, $data_dates);
		$simple->SetColor('orange@0.0');
		$simple->mark->SetType(MARK_FILLEDCIRCLE);
		$simple->mark->SetFillColor("red");
		$simple->mark->SetWidth(2);

		$graph->Add($simple);
		$graph->Stroke($file);
?>
