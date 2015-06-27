<?php

$password = $_GET['password'];
$email = trim($_GET['email']);

$salt = 'salT';
$passwordx = sprintf("%08x", crc32($salt.strtoupper($email)));

$link = mysql_connect('localhost', 'webuser', 'M6XQjGANttt8VQRA') or die('mysql_connect error');
$email2 = '"'.mysql_real_escape_string($email, $link).'"';
$password2 = '"'.$password.'"';
mysql_query("UPDATE members.Users SET count = count + 1 WHERE CURDATE() <= paid AND email = $email2 AND password = $password2", $link)
	or die('mysql_query UPDATE error');
if (mysql_affected_rows($link) != 1)
	header('HTTP/1.1 403 Forbidden');
else
	header('HTTP/1.1 200 OK');
mysql_close($link);
unset($link);

