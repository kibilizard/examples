<?php
$f = 'file.txt';
$s =  file_get_contents($f);
$s1 = unserialize($s);
function Cmp($x,$y)
{
	if ($x['number'] === $y['number']) return 0;
	return $x['number'] > $y['number'] ? 1 : -1;
}
uasort($s1,'Cmp');
?>
<div id="list" style="padding-top:20px; padding-left:20px;">
	<?php
	foreach($s1 as $point)
	{
		?><div><input type="hidden" value="<?php echo $point['id'];?>"><input type="text" value="<?php echo $point['value'];?>" style="border:0px;text-transform:uppercase;font-size:24px;cursor:pointer;"></div>
	<?php
	}
	?>
</div>
