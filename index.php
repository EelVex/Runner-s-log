<?php include('inc.php'); ?>
<!DOCTYPE html
PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang='el'>
	<head>
		<title>Running logs</title>
		<meta name='Description' content='Statistics from your running logs' />
		<meta name='author' content='EelVex' />
		<meta name='Keywords' content='running, log, stats' />
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link rel='stylesheet' title='Basic style' type='text/css' href='index.css' /> 
		<script type='text/javascript' src='flot/jquery.js'></script>
		<script type="text/javascript" src="flot/jquery.form.js"></script> 

		<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="stylesheet" type="text/css"/>
		<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>

		<script>
		$(document).ready(function() {
				$("#mera").datepicker();
				$("#mera").datepicker( "option", "dateFormat", "dd.mm.yy" );
				$('#mera').datepicker("setDate","0");
				$('#newsess').ajaxForm({ 
					dataType: 'json',
					success: readMSG
				}); 
			});
		function readMSG(data) { 
			alert(data.message); 
		}
		</script>
	</head>
<body>
	<div id='options'>
		<ul>
			<li><a href='index.php?show=sum' <?php echo $show['sum']; ?>>Περίληψη</a></li>
			<li><a href='index.php?show=cal' <?php echo $show['cal']; ?>>Ημερολόγιο</a></li>
			<li><a href='index.php?show=period' <?php echo $show['period']; ?>>Περίοδοι</a></li>
			<li><a href='index.php?show=stats' <?php echo $show['stats']; ?>>Διαγράμματα</a></li>
			<li><a href='index.php?show=guide' <?php echo $show['guide']; ?>>Οδηγός</a></li>
			<li><a href='index.php?show=logs' <?php echo $show['logs']; ?>>Logs</a></li>
		</ul>
	 </div>
	<div id='content'>
	<?php
	if (isset($_GET['show']))
		$show = $_GET['show'];
	else
		$show = '';
		switch ($show) {
		case 'logs':
			include('logs.php');
			break;
		case 'cal':
			include('cal.php');
			break;
		case 'guide':
			include('guide.php');
			break;
		case 'period':
			include('period.php');
			break;
		case 'stats':
			include('stats.php');
			break;
		case 'sum':
		default:
			include('summary.php');
			break;
		}
	?>
	</div>
</body>
</html>
