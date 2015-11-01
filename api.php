<?php
require 'inc/common.php';
require 'inc/db.php';

$password = $_GET['password'];
$email = trim($_GET['email']);

$salt = 'salT';
$passwordx = sprintf("%08x", crc32($salt.strtoupper($email)));

$email2 = $link->escapeString($email);
$password2 = $link->escapeString($password);
$link->exec("UPDATE Users SET count = count + 1 WHERE DATE('now') <= paid AND email = '$email2' AND password = '$password2'")
	or die('link->exec UPDATE error');
if ($link->changes() != 1)
	header('HTTP/1.1 403 Forbidden');
else
	header('HTTP/1.1 200 OK');
$link->close();
unset($link);
