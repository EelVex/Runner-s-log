<div class='table'>
	<table>
		<tr><th>km</th><th>mi</th><th>Laps 450m</th><th>Laps 400m</th></tr>
		<?
			$dist = 1;
			for ($i=0;$i<20;$i++) {
				echo "<tr>
					<td><b>".sprintf("%4.0f",$dist)."</b></td>
					<td>".sprintf("%4.2f", $dist*0.621371192)."</td>
					<td>".sprintf("%4.2f", $dist/.45)."</td>
					<td>".sprintf("%4.2f", $dist/.4)."</td>
					</tr>";
				$dist += 1;
			}
			for ($i=0;$i<5;$i++) {
				echo "<tr>
					<td><b>".sprintf("%4.0f",$dist)."</b></td>
					<td>".sprintf("%4.2f", $dist*0.621371192)."</td>
					<td>".sprintf("%4.2f", $dist/.45)."</td>
					<td>".sprintf("%4.2f", $dist/.4)."</td>
					</tr>";
				$dist += 5;
			}

		?>
	</table>
	<br />
	<table>
		<tr><th>mi</th><th>km</th><th>Laps 450m</th><th>Laps 400m</th></tr>
		<?
			$dist = 1;
			for ($i=0;$i<20;$i++) {
				echo "<tr>
					<td><b>".sprintf("%4.0f",$dist)."</b></td>
					<td>".sprintf("%4.2f", $dist*1.609344)."</td>
					<td>".sprintf("%4.2f", $dist*1.609344/.45)."</td>
					<td>".sprintf("%4.2f", $dist*1.609344/.4)."</td>
					</tr>";
				$dist += 1;
			}
		?>
	</table>
</div>

<div class='table'>
	<table>
		<tr><th>Ρυθμός</th><th>100m</th><th>1l<br />(400m)</th><th>1L<br/>(450m)</th><th>800m</th><th>7L</th><th>5km</th><th>10L</th><th>16L</th><th>10km</th><th>21.1km</th><th>42.2km</th></tr>
		<?
			$pace = 2+15/60;
			$distances = array(.1,.4,.45,.8,7*.45,5,10*.45,16*.45,10,21.1,42.2);
			for ($i=0;$i<54;$i++) {
				echo '<tr>
					<td><b>'.pace_format($pace).'</b></td>';
				foreach ($distances as $distance)
				{
					$vo = sprintf("%4.2f",daniels(1000/$pace, $pace*$distance));
					if ($distance >= 10) $ttime = time_format($pace*$distance);
					else $ttime = pace_format($pace*$distance);
					$color = '#fff';
					if ($vo < 29) $color = '#aaf';
					if ($vo > 31) $color = '#afa';
					if ($vo > 34) $color = '#ffa';
					if ($vo > 38) $color = '#faf';
					if ($vo > 40) $color = '#f5c';
					if ($vo > 50) $color = '#f88';
					echo "<td title='$vo' style='background: $color;'>$ttime</td>";
				}
				echo '</tr>';
				/*
				echo "<tr>
					<td><b>".pace_format($pace)."</b></td>
					<td>".pace_format($pace*.40)."</td>
					<td>".pace_format($pace*.45)."</td>
					<td>".pace_format($pace*7*.45)."</td>
					<td>".pace_format($pace*5)."</td>
					<td>".pace_format($pace*10*.45)."</td>
					<td>".pace_format($pace*16*.45)."</td>
					<td>".time_format($pace*10)."</td>
					<td>".time_format($pace*21.1)."</td>
					<td>".time_format($pace*42.2)."</td>
					</tr>";
				 */
				$pace += 5.0/60;
			}
?>
	</table>
</div>
