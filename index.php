<? include('inc.php'); ?>
<!DOCTYPE html
PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang='el'>
	<head>
		<title>EelVex running</title>
		<meta name='Description' content='EelVex Kostas Blekos: Personal Pages' />
		<meta name='author' content='EelVex Kostas Blekos' />
		<meta name='Keywords' content='Blekos, Mplekos, eelvex, Μπλέκος, μπλεκος,  freebsd, stories, projects' />
		<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-7" />
		<link rel='stylesheet' title='Basic style' type='text/css' href='index.css' /> 
	</head>
<body>
	<div id='options'>
		<ul>
			<li><a href='index.php?show=sum' <? echo $show['sum']; ?>>Περίληψη</a></li>
			<li><a href='index.php?show=cal' <? echo $show['cal']; ?>>Ημερολόγιο</a></li>
			<li><a href='index.php?show=period' <? echo $show['period']; ?>>Περίοδοι</a></li>
			<li><a href='index.php?show=stats' <? echo $show['stats']; ?>>Διαγράμματα</a></li>
			<li><a href='index.php?show=guide' <? echo $show['guide']; ?>>Οδηγός</a></li>
			<li><a href='index.php?show=logs' <? echo $show['logs']; ?>>Logs</a></li>
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
