<?php
include('functions.php');

function strip_speed($x)
{
	global $k, $a, $b, $l;
	return ($k*exp(-$a*$x) +$l*($x-$b) - ($k-$l*$b));
}

function speed($x)
{
	global $k, $a, $b, $l, $r, $s, $fmin;
	return $r*($k*exp(-$a*$x) +$l*($x-$b) - ($k-$l*$b))/$fmin+$s;
}

function ttime($star, $fin)
{
	global $k, $a, $b, $l, $r, $s, $fmin;

	$x = $fin;
	$limf = $r*(-$k*exp(-$a*$x)/$a +$l*($x*$x/2-$b*$x) - ($k-$l*$b)*$x)/$fmin+$s*$x;
	$x = $star;
	$lims = $r*(-$k*exp(-$a*$x)/$a +$l*($x*$x/2-$b*$x) - ($k-$l*$b)*$x)/$fmin+$s*$x;

	return $limf - $lims;
}

$k = 10;
$a = 0.2;
$b = 100;
$l = 1/4.17;
$fmin =  -strip_speed(-log($l/$k/$a)/$a, $k, $a, $b, $l);
$r = 0.65;
$s = 6.15;

if ($_GET['img'] == 'yes') {
	include("/usr/local/www/jpgraph-2.1.3/src/jpgraph.php");
	include("/usr/local/www/jpgraph-2.1.3/src/jpgraph_line.php");
	include("/usr/local/www/jpgraph-2.1.3/src/jpgraph_utils.inc.php");


	$f = new FuncGenerator("$r*($k*exp(-$a*\$x) +$l*(\$x-$b) - ($k-$l*$b))/$fmin+$s");
	list($xdata,$ydata) = $f->E(0, 42.1);

	//$f = new FuncGenerator('$x*$x');
	//list($x2data,$y2data) = $f->E(-2,2);

	// Setup the basic graph
	$graph = new Graph(450,350,"auto");
	$graph->SetScale("linlin");
	$graph->SetShadow();
	$graph->img->SetMargin(50,50,60,40);	
	$graph->SetBox(true,'black',2);	
	$graph->SetMarginColor('white');
	$graph->SetColor('lightyellow');

	// ... and titles
	$graph->title->Set('Ταχύτητα σε min/km');
	$graph->title->SetFont(FF_GREEK,FS_BOLD);
	//$graph->subtitle->Set("(With some more advanced axis formatting\nHiding first and last label)");
	//$graph->subtitle->SetFont(FF_FONT1,FS_NORMAL);
	$graph->xgrid->Show();

	$graph->yaxis->SetPos(0);
	$graph->yaxis->SetWeight(2);
	//$graph->yaxis->HideZeroLabel();
	$graph->yaxis->SetFont(FF_FONT1,FS_BOLD);
	$graph->yaxis->SetColor('black','darkblue');
	//$graph->yaxis->HideTicks(true,false);
	//$graph->yaxis->HideFirstLastLabel();

	$graph->xaxis->SetWeight(2);
	//$graph->xaxis->HideZeroLabel();
	//$graph->xaxis->HideFirstLastLabel();
	$graph->xaxis->SetFont(FF_FONT1,FS_BOLD);
	$graph->xaxis->SetColor('black','darkblue');

	$lp1 = new LinePlot($ydata,$xdata);
	$lp1->SetColor('blue');
	$lp1->SetWeight(2);

	//$lp2 = new LinePlot($y2data,$x2data);
	//list($xm,$ym)=$lp2->Max();
	//$lp2->SetColor('red');
	//$lp2->SetWeight(2);


	$graph->Add($lp1);
	//$graph->Add($lp2);
	$graph->Stroke();
	exit(0);
} 

?>
<?
$total_time = ttime(0, 42.1);
$half1_time = ttime(0, 21.1);
$half2_time = ttime(21.1, 42.1);
echo "Peak ταχύτητα: <br />";
echo "Αρχική ταχύτητα: ".pace_format($s)." <br />";
echo "Τελική ταχύτητα: ".pace_format(speed(42.1))." <br />";

echo "<img src='race.php?img=yes' />";
echo "<br />
	Χρόνος συνολικά: ".time_format($total_time)." (".pace_format($total_time/42.1)." min/km)
	<br />
	Χρόνος πρώτο μισό: ".time_format($half1_time)." (".pace_format($half1_time/21.1)." min/km)<br/>
	Χρόνος δεύτερο μισό: ".time_format($half2_time)." (".pace_format($half2_time/21.1)." min/km)<br/>";
?>
