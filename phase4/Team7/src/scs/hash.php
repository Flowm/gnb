<?php 

$key="94708197442006051920589";
$amount = "150.02";
$pin = "123456789112345";
$dest = "019";
$data = $pin.$amount.$dest;
echo "data is ".$data;
echo "<br>";
$hsh = hash ('sha256', $data.$key);
$output = substr($hsh, 0, 15);


echo $output













?>
