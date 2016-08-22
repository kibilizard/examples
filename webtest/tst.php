<?php
$f = 'file.txt';
$s = array();
for($i=0;$i<5;$i++)
{
	$s[]=array(
		'id' => $i,
		'value' => 'post'.$i,
		'number' => $i);
}
$s1 = serialize($s);
file_put_contents($f,$s1);
?>