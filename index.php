<?php
require 'inc/common.php';
require 'inc/mailer.php';

function mail_and_die($m)
{
  mailer('it@xinchejian.com', 'Error in '.__FILE__, $m);
  die($m);
}

// add SetEnv MYSQL_PASSWORD "blah" to this site's Apache conf
$link = mysql_connect('localhost', 'webuser', getenv('MYSQL_PASSWORD'))
	or mail_and_die('mysql_connect error');

// Find known MAC address
$mac = find_mac();
if ($mac)
	$mac2 = '"'.mysql_real_escape_string($mac, $link).'"';
else
	$mac2 = '"whatever"';

mysql_query("UPDATE members.Users SET count = count + 1 WHERE CURDATE() <= paid AND mac = SHA1(CONCAT('salT',$mac2))", $link)
	or mail_and_die('mysql_query UPDATE error');

if (mysql_affected_rows($link) == 1)
{
	open_door();
}
else {
	header('Location: index.html', true, 303);
}
mysql_close($link);
unset($link);

