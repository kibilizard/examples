<?php
$p =  file_get_contents("php://input");
$x = json_decode($p);
print_r($x);
$data = array();
foreach ($x as $s)
{
	echo 'ok!';
	$data[] = array(
		'id' => $s->{id},
		'value' => $s->{value},
		'number' => $s->{number});
}
print_r($data);
$f = 'file.txt';
$s1 = serialize($data);
file_put_contents($f,$s1);
//print_r($_POST);
?>