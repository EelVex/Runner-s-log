<?php

global $CONFIGURE, $record_categories;

$CONFIGURE = array(
	/* The file where you record your runs */
	'log_file' => 'samples/example.log',

	/* Log files of extra activities that you want to appear on the 
	 * calendar. */
	'extra_activities' => array(
		'wei' => 'samples/example-weights.log',
		'swim' => 'samples/example-swim.log',
		'misc' => 'samples/example-misc.log'
	),

	/* What separates the fields in a log file. Use comma (,) for csv 
	 * format */
	'field_separator' => "\t",

	/* A lap's length in meters; 
	 * use '1' if you log running distance in meters
	 * use '1000' if you log in km
	 * use '400' if you log track laps
	 * use '1609.344' if you log miles */
	'lap_distance_m' => 1000,

	/* Monthly distance goal in km */
	'goal' => 108,

	/* Show months in 'reverse' or 'normal' order */
	'calendar_order' => 'normal', 
);

/* Mark km for which records will be shown in "summary" page. 
 * Record distances span 10% of the mark. */
$record_categories = array(3, 5, 7.2, 10, 15, 21.1, 42.2);

?>
