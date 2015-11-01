<?php
require 'inc/common.php';
require 'inc/mailer.php';
require 'inc/db.php';


$password = $_POST['password'];
$password2 = $link->escapeString($password);

// Register MAC address
$mac = find_mac();
if ($mac)
	$mac2 = ", mac = '".sha1('salT'.$mac)."'";
else
	$mac2 = '';

$link->exec('UPDATE Users SET count = count + 1'.$mac2." WHERE DATE('now') <= paid AND password = '$password2'")
	or mail_and_die('link->exec UPDATE error', __FILE__);

if ($link->changes() != 1)
{
	header('Location: accessdenied.html', true, 303);
}
else
{
	open_door();
}
$link->close();
unset($link);

