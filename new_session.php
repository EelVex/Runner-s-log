<?php
require_once('inc.php');
$separator = $CONFIGURE['field_separator'];

if($_POST) {
// Assume everything is ok.
	$date = $_POST['mera'];
	$time = $_POST['wra_h'].':'.$_POST['wra_m'];
	$duration = strval($_POST['diarkeia_h']*60 + $_POST['diarkeia_m']).':'.$_POST['diarkeia_s'];
	$laps = $_POST['laps'];
	$flag = $_POST['flag'];
	$comments = $_POST['comments'];
	$str = "$date$separator$time$separator$duration$separator$laps$separator$flag$separator$comments\n";

	$file = $CONFIGURE['log_file'];
	if (is_writable($file)) {
		$fp = fopen($file,'a');
		fwrite($fp, $str);
		echo 'Αποθηκεύτηκε!';
	} else {
		echo "No permission to write to $file";
	}
}

?>
