<?

$first_stamp = $runs[0]['datetime_stamp'];
$last_stamp = $runs[sizeof($runs)-1]['datetime_stamp'];

$first_m = date("n", $first_stamp); $first_y = date("Y", $first_stamp);
$last_m = date("n", $last_stamp); $last_y = date("Y", $last_stamp);

	echo "<div style='display: block; margin: 5px auto 20px auto; width: 500px'>";
	show_month(date('m'),date('Y'));
	echo "</div>";
	echo "<hr style='color: #fff; border: dotted #888 1px' />";

/* Reverse order: */
/*
$m = $last_m; $y = $last_y;
while (!($m == $first_m-1 && $y == $first_y)) {
	echo "<div style='display: block; margin: 5px; height: 430px'>";
	show_month($m, $y);
	echo "</div>";
	$m = ($m+10)%12+1;
	if ($m==12) $y--;
}
 */
/* "Normal" order: */
$m = $first_m-1; $y = $first_y;
while (!($m == $last_m && $y == $last_y)) {
	if ($m==12) $y++;
	$m = $m%12+1;
	echo "<div style='display: block; margin: 5px; height: 430px'>";
	show_month($m, $y);
	echo "</div>";
}

?>
