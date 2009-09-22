<html lang='el'>
<head>
		<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-7" />
<style>
#table {
	float: left;
	margin: 3px;
}
.graph {
	float: left;
	margin: 3px;
}
table {
	font-family: sans-serif;
	font-size: 10px;
	border: solid black 1px;
	border-spacing: 0;
}
th {
	background-color: #c95;
}
td {
	border-top: solid #ccc  1px;
	border-right: solid #ccc 1px;
	text-align: right;
}
tr {
}
img {
	padding :0;
	margin: 0;
	border: solid black 1px;
}
table img {
	border: 0;
}
table.pal-cal {
	margin: 2px 0;
}
table.pal-cal td {
	color: green;
	width: 140px;
}
table.pal-cal td.pal-month,
table.pal-cal td.pal-dayname,
table.pal-cal td b {
	color: black;
}
</style>
</head>
<body>
<?php
setlocale(LC_ALL,'el_GR.ISO8859-7');

// Get the data
include_once("read.php");
foreach($runs as $run) {
	$m = strftime("%b %Y",$run['datetime_stamp']);
	$monthly_km[$m] += $run['distance'];
	$monthly_f[$m]++;
}


if ($best_km == sizeof($runs)-1) {
	printf("<h1>New record distance: %03.2f</h1>",
		$runs[sizeof($runs)-1]["distance"]
	);
}
if ($best_pace == sizeof($runs)-1) {
	printf("<h1>New record pace: %01.0f:%02.0f</h1>",
		intval($runs[sizeof($runs)-1]["minutes_per_km"]),
		60*($runs[sizeof($runs)-1]["minutes_per_km"]- intval($runs[sizeof($runs)-1]["minutes_per_km"]))
	);
}


$record_km = award_image('km', $record_days_km);
$record_pc = award_image('pc', $record_days_pc);
$record_vo = award_image('vo', $record_days_vo);


echo "<div><table><tr><th>&nbsp;</th><th> 14 μέρες </th><th>Προηγούμενη</th><th>Συνολικά</th></tr>
	<tr><td style='text-align: left;'><b>μ.ο. Km</b></td><td>$mean_km_14<img src='arrow_".((1*$mean_km_14>1*$mean_km)?"up":"down").".gif' alt='updown' /></td><td> $record_km ".sprintf("%4.2f",$runs[sizeof($runs)-1]['distance'])."<img src='arrow_".(($runs[sizeof($runs)-1]['distance']>$mean_km)?"up":"down").".gif' alt='updown' /></td><td>$mean_km</td></tr>
	<tr><td style='text-align: left;'><b>Km</b></td><td>$mean_total_km_14<img src='arrow_".(($mean_total_km_14>14*$mean_total_km)?"up":"down").".gif' alt='updown' /></td><td> -&nbsp;&nbsp; </td><td>".sprintf("%4.2f",$mean_total_km*14)."</td></tr>
	<tr><td style='text-align: left;'><b>Ρυθμός</b></td><td>$mean_pace_14<img src='arrow_".(($mean_pace_14<$mean_pace)?"up":"down").".gif' alt='updown' /></td><td> $record_pc ".sprintf("%4.2f",$runs[sizeof($runs)-1]['minutes_per_km'])."<img src='arrow_".(($runs[sizeof($runs)-1]['minutes_per_km']<$mean_pace)?"up":"down").".gif' alt='updown' /><td>$mean_pace</td></tr>
	<tr><td style='text-align: left;'><b>VO<sub>2</sub></b></td><td>$mean_vo_14<img src='arrow_".(($mean_vo_14>$mean_vo)?"up":"down").".gif' alt='updown' /></td><td> $record_vo ".sprintf("%4.2f",$runs[sizeof($runs)-1]['vo'])."<img src='arrow_".(($runs[sizeof($runs)-1]['vo']>$mean_vo)?"up":"down").".gif' alt='updown' /></td><td>$mean_vo</td></tr>
	</table></div>";

echo "<div style='margin-top: 5px'><table><tr><th>Κατηγορία</th><th>Ημερομηνία</th><th>Χρόνος</th><th>Απόσταση</th><th>Ρυθμός</th> </tr>
	<tr><td style='text-align: left;'><b>10km</b></td><td>15-Μαι-2007</td><td>20:00</td><td>9.90km</td><td>4:00</td></tr>
	</table></div>";

echo "<div id='table'><table><tr><th>Ημ/νια</th><th>Απόσταση<br />(km)</th><th>Χρόνος<br />(h:m:s)</th><th>Μ.Ο<br />(min/km)</th><th>&nbsp;</th><th>VO</th></tr>";
for ($i=sizeof($runs)-1;$i>=0;$i--) {
	$style = "";  $img_pc = ''; $img_km = ''; $img_vo = '';
	/*
	if (sizeof($runs)-$i < 14) {
		$img_pc = award_image('pc', $runs[$i]['record_days_pc']); 
		$img_km = award_image('km', $runs[$i]['record_days_km']);
		$img_vo = award_image('vo', $runs[$i]['record_days_vo']);
	}
	if (sizeof($runs)-$i == 14) {
		$style = "style='border-bottom: solid black 1px'";
	}
	 */
	$flag = $runs[$i]["type_of_run"];
	if ($best_pace == $i) { //$style = " style='background: #fcc;' "; 
				$img_pc = '<img src=\'cup.gif\' />'; }
	if ($best_km == $i) { //$style = " style='background: #cfc;' "; 
				$img_km = '<img src=\'cup.gif\' />'; }
	switch (substr($flag,0,1)) {
		case "R":
			$flag_style = "style='text-align: center; background-color: #c7a'";
			break;
		case "M":
			$flag_style = "style='text-align: center; background-color: #ffc'";
			break;
		case "S":
			$flag_style = "style='text-align: center; background-color: #ccf'";
			break;
		case "L":
			$flag_style = "style='text-align: center; background-color: #cfc'";
			break;
		default:
			$flag_style = "style='text-align: center;'";
			break;
	}
	$secs = round(60*($runs[$i]["minutes_per_km"]-intval($runs[$i]["minutes_per_km"])));
	$mins = intval($runs[$i]["minutes_per_km"])+floor($secs/60);
	$secs = $secs%60;
	printf("<tr $style>
		<td $style>%s</td>
		<td $style>$img_km%01.2f&nbsp;</td>
		<td $style>&nbsp;%01.0f:%02.0f:%02.0f&nbsp;</td>
		<td $style>$img_pc%01.0f:%02.0f &nbsp;</td>
		<td $flag_style $style>%s</td>
		<td $style>$img_vo %3.1f</td>
		</tr>",
		strftime("%a %d-%b-%Y",$runs[$i]["datetime_stamp"]),
		$runs[$i]["distance"],
		$runs[$i]["time_hours"],
		$runs[$i]["time_mins"],
		$runs[$i]["time_secs"],
		$mins, $secs,
		$flag,
		$runs[$i]['vo']
	);
	//echo "<tr>
		//<td>".strftime("%a %d-%b-%Y",$runs[$i]["datetime_stamp"])."</td>
		//<td>".$runs[$i]["distance"]."&nbsp;&nbsp;</td>
		//<td>".$runs[$i]["time_hours"].":".$runs[$i]["time_mins"].":".$runs[$i]["time_secs"]."&nbsp;&nbsp;</td>
		//<td>".$runs[$i]["minutes_per_km"]."&nbsp;&nbsp;</td>
		//</tr>";
}
echo "</table><br />";
echo "<table><tr><th>Μήνας</th><th>km</th><th>runs</th><th>km/run</th></tr>";
foreach($monthly_km as $this_m=>$this_km)  echo "<tr><td>$this_m</td><td>".number_format($this_km,2,',','.')."</td><td>".$monthly_f[$this_m]."</td><td>".number_format($this_km/$monthly_f[$this_m],2,',','.')."</td></tr>"; 
echo "</table></div>";


echo "<div class='graph'><img src='monthly_km.php' /></div>";

echo "<div class='graph'><img src='images.php?graph=km' /></div>";

//echo "<div class='graph'><img src='images.php?graph=mean_km' /></div>";

echo "<div class='graph'><img src='images.php?graph=pace' /></div>";

echo "<div class='graph'><img src='images.php?graph=mean_pace' /></div>";

echo "<div class='graph'><img src='images.php?graph=vo' /></div>";

echo "<div class='graph'>";
system("pal --html -f /home/eelvex/.pal/web.conf");
echo "</div>";



?>
</body>
</html>
