<?php
require 'inc/common.php';
require 'inc/mailer.php';
require 'inc/db.php';

// Find known MAC address
$mac = find_mac();
if ($mac)
	$mac2 = '"'.$link->escapeString($mac).'"';
else
	$mac2 = '"whatever"';

$link->exec("UPDATE Users SET count = count + 1 WHERE date('now') <= paid AND mac = SHA1('salT' || $mac2)")
	or mail_and_die('mysql_query UPDATE error', __FILE__);

if ($link->changes() == 1)
{
	open_door();
}
else {
	header('Location: index.html', true, 303);
}
$link->close();
unset($link);

