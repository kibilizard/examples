<?php
$x = array (5, 10, 7, 11, 5, 13, 10, 5, 11, 5, 12, 8);
foreach ($x as $p)
{
	echo '<input type="hidden" value="'.$p.'">';
}
?>
<div style="width:310px;position:fixed; left:0px; right:0px; top:130px; margin:auto;">
	<canvas id="holst" width="281" height="301"></canvas>
	<div style="float:right">
		<img id="line" src="line.png" style="float:top; width:20px; height:20x; cursor:pointer;">
		<img id="histo" src="histo.png" style="float:top; width:20px; height:20x; cursor:pointer;">
	</div>
</div>