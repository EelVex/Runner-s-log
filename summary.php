<?php
$goal = $CONFIGURE['goal'];
$month_km = 0;
if (array_key_exists(strftime("%b %Y"),$monthly_km)) $month_km = distance_format($monthly_km[strftime("%b %Y")]);
$percent = round($month_km/$goal*100);
$ruler = "<div style='display: block; border: solid black 0px; width: 600px; height: 15px;'>
<div style='float: left; border: solid black 1px; border-right: 0px; font-family: monospace; background: #494; height: 15px; width: ".round($percent*5)."px;'> ${month_km} km</div>
<div style='float: left; border: black solid 1px; border-left: #ccc dotted 1px; height: 15px; width: ".(500-round($percent*5))."px; font-family: monospace'>${percent}%</div>
</div>";
?>
<div class='table'>
	<table>
		<tr><th>&nbsp;</th><th>Km</th><th>�.�. Km/<br/>���������</th><th>������</th><th>VO<sub>2</sub></th></tr>
		<tr><td>���������</td>
			<td><?php echo sprintf("%4.2f",$runs[sizeof($runs)-1]['distance']); ?></td>
			<td> -&nbsp;&nbsp; </td>
			<td><?php echo pace_format($runs[sizeof($runs)-1]['minutes_per_km']); ?></td>
			<td><?php echo sprintf("%4.2f",$runs[sizeof($runs)-1]['vo']); ?></td>
			</tr>
		<tr><td>�����������</td>
			<td><?php echo sprintf("%4.2f",$runs[sizeof($runs)-2]['distance']); ?></td>
			<td> -&nbsp;&nbsp; </td>
			<td><?php echo pace_format($runs[sizeof($runs)-2]['minutes_per_km']); ?></td>
			<td><?php echo sprintf("%4.2f",$runs[sizeof($runs)-2]['vo']); ?></td>
			</tr>
		<tr><td>14 �����</td>
			<td><?php echo "$mean_total_km_14"; ?></td>
			<td><?php echo "$mean_km_14"; ?></td>
			<td><?php echo pace_format($mean_pace_14); ?></td>
			<td><?php echo "$mean_vo_14"; ?></td>
			</tr>
		<tr><td>������</td>
			<td><?php echo sprintf("%4.2f",$total_run); ?></td>
			<td><?php echo "$mean_km"; ?></td>
			<td><?php echo pace_format($mean_pace); ?></td>
			<td><?php echo "$mean_vo"; ?></td>
			</tr>
	</table>
</div>

<div style='clear: both; padding: 3px 0'>
	<p class='plain'>
		<?php echo $ruler; ?>
	</p>
	<p class='plain'>
		Bonus: <?php echo $record_km.$record_pc.$record_vo; ?> </p>
	<p class='plain'>
		�� �������� �� ��� ���� ��������� � <b>���������</b> ����
		 <?php echo "<b>".(($runs[sizeof($runs)-1]['distance']>$mean_km)?"�����������":"��������")."</b><img src='img/arrow_".(($runs[sizeof($runs)-1]['distance']>$mean_km)?"up":"down").".gif' alt='updown' />"; ?> ����������, 
		��� <?php echo "<b>".(($runs[sizeof($runs)-1]['minutes_per_km']<$mean_pace)?"�������":"����")."</b><img src='img/arrow_".(($runs[sizeof($runs)-1]['minutes_per_km']<$mean_pace)?"up":"down").".gif' alt='updown' />"; ?> ��� �� 
		<?php echo '<b>'.(($runs[sizeof($runs)-1]['vo']>$mean_vo)?"���������":"����������")."</b><img src='img/arrow_".(($runs[sizeof($runs)-1]['vo']>$mean_vo)?"up":"down").".gif' alt='updown' />"; ?> VO<sub>2</sub>.
	</p>
	<p class='plain'>
		�� �������� �� ��� ���� ��������� � <b>����� ���� 14�� ������</b> ����
		<?php echo '<b>'.((1*$mean_km_14>1*$mean_km)?"�����������":"��������")."</b><img src='img/arrow_".((1*$mean_km_14>1*$mean_km)?"up":"down").".gif' alt='updown' />"; ?> ����������, 
		��� <?php echo '<b>'.(($mean_pace_14<$mean_pace)?"�������":"����")."</b><img src='img/arrow_".(($mean_pace_14<$mean_pace)?"up":"down").".gif' alt='updown' />"; ?> ��� ��  
		<?php echo '<b>'.(($mean_vo_14>$mean_vo)?"���������":"����������")."</b><img src='img/arrow_".(($mean_vo_14>$mean_vo)?"up":"down").".gif' alt='updown' />"; ?> VO<sub>2</sub>.
		</p>
	<p class='plain'>
	�� ���������� 14 ����� ����� <?php echo '<b>'.(($mean_total_km_14>14*$mean_total_km)?"�����������":"��������")."</b><img src='img/arrow_".(($mean_total_km_14>14*$mean_total_km)?"up":"down").".gif' alt='updown' />"; ?> ���������� ��� ��� ���� ������� 14�� ������ <?php echo "(".sprintf("%4.2f",14*$mean_total_km)."km)"; ?><br />
	</p>

</div>


<div class='table'><table><tr><th colspan='7' style='border-bottom: solid black 1px'>�����</th></tr><tr><th>���������</th><th>����������</th><th>������</th><th>Projected<br />������</th><th>��������</th><th>������</th><th>VO<sub>2</sub></th></tr>
<?php
foreach ($record_categories as $cat) {
	$cat = strval($cat);
	if (array_key_exists($cat, $record_for_category)) {
		echo " <tr><td style='text-align: left;'><b>$cat Km</b></td>
			<td>".strftime("%a %d-%b-%Y",$record_for_category[$cat]["datetime_stamp"])."</td>
			<td>".sprintf("%02.0f:%02.0f:%02.0f",$record_for_category[$cat]['time_hours'],$record_for_category[$cat]['time_mins'],$record_for_category[$cat]['time_secs'])."</td>
			<td>".sprintf("%02.0f:%02.0f:%02.0f",floor($cat*$record_for_category[$cat]['pace']/60),floor($cat*$record_for_category[$cat]['pace']%60),(60*$cat*$record_for_category[$cat]['pace'])%60)."</td>
			<td>".sprintf("%4.2f",$record_for_category[$cat]['distance'])."km</td>
			<td>".pace_format($record_for_category[$cat]['pace'])."</td>
			<td>".sprintf("%4.2f", $record_for_category[$cat]['vo'])."</td>
			</tr>";
	}
}
?>
	</table></div>

<?php 
if (isset($races) && sizeof($races) > 0) {
	echo "<div class='table'><table><tr><th colspan='7' style='border-bottom: solid black 1px'>������</th></tr><tr><th>������</th><th>����������</th><th>������</th><th>��������</th><th>������</th><th>VO<sub>2</sub></th></tr>";
	foreach ($races as $race) {
		echo " <tr><td style='text-align: left;'><b>".$race['comments']."</b></td>
			<td>".strftime("%a %d-%b-%Y",$race["datetime_stamp"])."</td>
			<td>".sprintf("%02.0f:%02.0f:%02.0f",$race['time_hours'],$race['time_mins'],$race['time_secs'])."</td>
			<td>".sprintf("%4.2f",$race['distance'])."km</td>
			<td>".pace_format($race['pace'])."</td>
			<td>".sprintf("%4.2f", $race['vo'])."</td>
			</tr>";
	}
	echo "</table></div>";
}
?>
