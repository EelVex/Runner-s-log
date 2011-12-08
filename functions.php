<?php
function daniels($v, $t)
{
	// Predicted
	return (-4.60 + 0.182258*$v + 0.000104*$v*$v)/(0.8 + 0.1894393 * exp(-0.012778 * $t) + 0.2989558 * exp(-0.1932605 * $t));
	// Actual
	//return -4.60 + 0.182258*$v + 0.000104*$v*$v;
}

function award_image($type, $days) 
{
	//$ret = "($days)";
	$ret = '';

	switch ($type) {
		case "km": $type = '1'; $type_name = 'distance'; break;
		case "pc": $type = '2'; $type_name = 'pace'; break;
		case "vo": $type = '3'; $type_name = 'VO2'; break;
	}

	if 	($days > 89) $ret = "<img src='img/award_star_gold_$type.png' title='$days' alt='gold' /> ($type_name)";
	elseif 	($days > 29) $ret = "<img src='img/award_star_silver_$type.png' title='$days' alt='silver' /> ($type_name)";
	elseif 	($days > 6) $ret = "<img src='img/award_star_bronze_$type.png' title='$days' alt='bronze' /> ($type_name)";

	return $ret;
}

function pace_format($pace)
{
	$pace = round(60*$pace);
	return sprintf("%02.0f:%02.0f", floor($pace/60), $pace%60);
}

function time_format($minutes)
{
	$secs = round(60*$minutes);
	if ($secs > 3600*24) 
		return sprintf("%d+%02.0f:%02.0f:%02.0f", 
			floor($secs/3600/24),
			floor($secs/3600)%24,
			floor($secs/60)%60,
			$secs%60
		);
	else 
		return sprintf("%02.0f:%02.0f:%02.0f", 
		floor($secs/3600),
		floor($secs/60)%60,
		$secs%60
	);
}

function distance_format($kms, $d=2)
{
	return sprintf("%3.${d}f", $kms);
}

function stamp_format($stamp)
{
	global $runs;

	if ($stamp == $runs[sizeof($runs)-1]['datetime_stamp'])
		return "Σήμερα";
	else 
		return strftime("%a %d-%b-%Y",$stamp);
}

function style_type($type,$back='background-')
{
	switch ($type) {
	case "R":
		$flag_style = "style='text-align: center; ${back}color: #c7a'";
		break;
	case "M":
		$flag_style = "style='text-align: center; ${back}color: #ffc'";
		break;
	case "S":
		$flag_style = "style='text-align: center; ${back}color: #ccf'";
		break;
	case "L":
		$flag_style = "style='text-align: center; ${back}color: #cfc'";
		break;
	case "H":
	case "T":
	case "I":
		$flag_style = "style='text-align: center; ${back}color: #faa'";
		break;
	default:
		$flag_style = "style='text-align: center;'";
		break;
	}
	return $flag_style;
}

function show_month($mon, $year)
{
	global $runs, $week, $extra_activities;

	$month = array();

	for ($i=1;$i<40;$i+=7) {
		$extra_weeks[date('W', mktime(0,0,0, $mon, $i, $year))] = ' 0.0 km';
	}
	foreach ($runs as $run) {
		$stamp = $run['datetime_stamp'];
		if ((date("n", $stamp) < $mon-1 || date("Y", $stamp) < $year)) 
			continue;
		if (isset($week[$year][date('W',$stamp)]))
			$extra_weeks[date('W',$stamp)] = distance_format($week[$year][date('W',$stamp)],1).' km';
		//else $extra_weeks[date('W',$stamp)] = 0;
		if (date("n", $stamp) == $mon && date("Y", $stamp) == $year) {
			$day = date("j", $stamp);
			//$content = $day.'<br/>'.$run['distance'];
			$content = '<span '.style_type($run['type_of_run'],'background-').' title=\''.$run['comments'].'\'>'.distance_format($run['distance']).'km<br />'.pace_format($run['pace']).'p</span>';
			$month[$day] = array(NULL, NULL, $content);
		}
		if (date("n", $stamp) > $mon && date("Y", $stamp) >= $year)
			break;
	}

	foreach ($extra_activities as $activity_name => $activity) 
	{
		foreach ($activity as $instance)
		{
			$stamp = $instance['datetime_stamp'];
			if ((date("n", $stamp) < $mon-1 || date("Y", $stamp) < $year)) 
				continue;
			if (date("n", $stamp) == $mon && date("Y", $stamp) == $year) 
			{	
				$day = date("j", $stamp);
				$what = '';
				if ($instance['what'] != '') $what = ': '.$instance['what'];
				$content = '<span class=\''.$activity_name.'\' title=\''.($instance['comments']).' '.time_format($instance['duration']).'\'>'.substr($activity_name, 0, 1).$what.'</span>';
				if (isset($month[$day][2])) $month[$day][2] .= $content;
				else $month[$day][2] = $content;
			}
			if (date("n", $stamp) > $mon && date("Y", $stamp) >= $year)
				break;
		}

	}

	$extra = array_values($extra_weeks);

	echo generate_calendar($year, $mon, $month, 3, NULL, 1, 15, $extra);

}


function generate_calendar($year, $month, $days = array(), $day_name_length = 3, $month_href = NULL, $first_day = 0, $pn = array(), $extra = array()){
	# PHP Calendar (version 2.3), written by Keith Devens
	$first_of_month = gmmktime(0,0,0,$month,1,$year);
	$week = 0;

	#remember that mktime will automatically correct if invalid dates are entered
	# for instance, mktime(0,0,0,12,32,1997) will be the date for Jan 1, 1998
	# this provides a built in "rounding" feature to generate_calendar()

	$day_names = array(); #generate all the day names according to the current locale
	for($n=0,$t=(3+$first_day)*86400; $n<7; $n++,$t+=86400) #January 4, 1970 was a Sunday
		$day_names[$n] = ucfirst(gmstrftime('%A',$t)); #%A means full textual day name

	list($month, $year, $month_name, $weekday) = explode(',',gmstrftime('%m,%Y,%B,%w',$first_of_month));
	$weekday = ($weekday + 7 - $first_day) % 7; #adjust for $first_day
	$title   = (ucfirst($month_name)).'&nbsp;'.$year;  #note that some locales don't capitalize month and day names

	#Begin calendar. Uses a real <caption>. See http://diveintomark.org/archives/2002/07/03
	@list($p, $pl) = each($pn); @list($n, $nl) = each($pn); #previous and next links, if applicable
	if($p) $p = '<span class="calendar-prev">'.($pl ? '<a href="'.htmlspecialchars($pl).'">'.$p.'</a>' : $p).'</span>&nbsp;';
	if($n) $n = '&nbsp;<span class="calendar-next">'.($nl ? '<a href="'.htmlspecialchars($nl).'">'.$n.'</a>' : $n).'</span>';
	$calendar = '<table class="calendar">'."\n".
		'<caption class="calendar-month">'.$p.($month_href ? '<a href="'.htmlspecialchars($month_href).'">'.$title.'</a>' : $title).$n."</caption>\n<tr>";

	if($day_name_length){ #if the day names should be shown ($day_name_length > 0)
		#if day_name_length is >3, the full name of the day will be printed
		foreach($day_names as $d)
			$calendar .= '<th abbr="'.($d).'">'.($day_name_length < 4 ? substr($d,0,$day_name_length) : $d).'</th>';
		$calendar .= "<th class='extra'>&nbsp;</th></tr>\n<tr>";
	}

	if($weekday > 0) $calendar .= '<td colspan="'.$weekday.'">&nbsp;</td>'; #initial 'empty' days
	for($day=1,$days_in_month=gmdate('t',$first_of_month); $day<=$days_in_month; $day++,$weekday++){
		if($weekday == 7){
			$weekday   = 0; #start a new week
			$calendar .= "<td class='extra'>".$extra[$week++]."</td></tr>\n<tr>";
		}
		$today = ''; if ($day == date('j') && $month == date('m') && $year == date('Y'))
			$today = ' class=\'today\' ';
		if(isset($days[$day]) and is_array($days[$day])){
			@list($link, $classes, $content) = $days[$day];
			//if(is_null($content))  $content  = $day;
			$calendar .= '<td'.$today.($classes ? ' class="'.htmlspecialchars($classes).'">' : '>')."<span class='day'>$day</span>".
				($link ? '<a href="'.htmlspecialchars($link).'">'.$content.'</a>' : $content).'</td>';
		}
		else $calendar .= "<td $today><span class='day'>$day</span></td>";
	}
	if($weekday != 7) $calendar .= '<td colspan="'.(7-$weekday).'">&nbsp;</td>'; #remaining "empty" days

	return $calendar."<td class='extra'>".$extra[$week]."</td></tr>\n</table>\n";
}

function week_stats($day, $runs)
{
	if ($day%7 != 0 || $day == 0) return '';

	$km = 0;
	$day += date("z", $runs[0]['datetime_stamp']);
	foreach($runs as $run) {
		$this_day = date("z", $run['datetime_stamp']);
		if ($this_day>=$day-7 && $this_day<$day) {
			$km += $run['distance'];
		}
	}

	return "<span class='week_stats'>".distance_format($km,1)."</span>";
}

?>
