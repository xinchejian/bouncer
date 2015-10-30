<?php
require 'inc/common.php';
require 'inc/mailer.php';


$password = $_POST['password'];

// add SetEnv MYSQL_PASSWORD "blah" to this site's Apache conf
$link = mysql_connect('localhost', 'webuser', getenv('MYSQL_PASSWORD'))
	or mail_and_die('mysql_connect error', __FILE__);

$password2 = '"'.mysql_real_escape_string($password, $link).'"';

// Register MAC address
$mac = find_mac();
if ($mac)
	$mac2 = ', mac = SHA1(CONCAT("salT","'.mysql_real_escape_string($mac, $link).'"))';
else
	$mac2 = '';

mysql_query('UPDATE members.Users SET count = count + 1'.$mac2." WHERE CURDATE() <= paid AND password = $password2", $link)
	or mail_and_die('mysql_query UPDATE error', __FILE__);

if (mysql_affected_rows($link) != 1)
{
	header('Location: accessdenied.html', true, 303);
}
else
{
	open_door();
}
mysql_close($link);
unset($link);

