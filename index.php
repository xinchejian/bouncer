<?php
require 'inc/common.php';
require 'inc/mailer.php';
require 'inc/db.php';

// Find known MAC address
$mac = find_mac();
if ($mac)
	$mac2 = sha1('salT'.$mac);
else
	$mac2 = 'whatever';

$link->exec("UPDATE Users SET count = count + 1 WHERE DATE('now') <= paid AND mac = '$mac2'")
	or mail_and_die('link->exec UPDATE error', __FILE__);

if ($link->changes() == 1)
{
	open_door();
}
else {
	header('Location: index.html', true, 303);
}
$link->close();
unset($link);

