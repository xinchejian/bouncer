<?php
require 'inc/common.php';
require 'inc/mailer.php';
require 'inc/db.php';

// Find known MAC address
$mac = find_mac();
if ($mac)
	$mac2 = '"'.mysql_real_escape_string($mac, $link).'"';
else
	$mac2 = '"whatever"';

mysql_query("UPDATE members.Users SET count = count + 1 WHERE CURDATE() <= paid AND mac = SHA1(CONCAT('salT',$mac2))", $link)
	or mail_and_die('mysql_query UPDATE error', __FILE__);

if (mysql_affected_rows($link) == 1)
{
	open_door();
}
else {
	header('Location: index.html', true, 303);
}
mysql_close($link);
unset($link);

