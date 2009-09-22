<?php
echo "<div class='table'><table><tr><th>Ημ/νια</th><th>Απόσταση<br />(km)</th><th>Χρόνος<br />(h:m:s)</th><th>Μ.Ο<br />(min/km)</th><th>&nbsp;</th><th>VO</th></tr>";
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
	
	$flag_style = style_type(substr($flag,0,1));
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
echo "</table></div>";
echo "<div class='table'><table><tr><th>Μήνας</th><th>km</th><th>runs</th><th>km/run</th></tr>";
foreach($monthly_km as $this_m=>$this_km)  echo "<tr><td>$this_m</td><td>".number_format($this_km,2,',','.')."</td><td>".$monthly_f[$this_m]."</td><td>".number_format($this_km/$monthly_f[$this_m],2,',','.')."</td></tr>"; 
echo "</table></div>";


?>
