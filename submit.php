<?php

$amount = $_POST['amount'];
$email = trim($_POST['email']);
if ($amount == '100')
	$months = 1;
else if ($amount == '450')
	$months = 6;
else
	die('wrong amount');
if (!filter_var($email, FILTER_VALIDATE_EMAIL))
	die('invalid email');

$salt = 'salT';
$password = sprintf("%08x", crc32($salt.strtoupper($email)));

$link = mysql_connect('localhost', 'webuser', 'M6XQjGANttt8VQRA') or die('mysql_connect error');
$email2 = '"'.mysql_real_escape_string($email, $link).'"';
$amount2 = '"'.mysql_real_escape_string($amount, $link).'"';
$password2 = '"'.$password.'"';
$salt2 = '"'.$salt.'"';
mysql_query("INSERT IGNORE members.Users (email,since,password,salt) VALUES($email2,NOW(),$password2,$salt2)", $link)
	or die('mysql_query INSERT Users error');
// benefit of the doubt:
mysql_query("UPDATE members.Users SET paid = IF(CURDATE()<paid,paid,CURDATE()) + INTERVAL $months MONTH WHERE email = $email2", $link)
	or die('mysql_query UPDATE error');
if (mysql_affected_rows($link) != 1)
	die('mysql_affected_rows error');
mysql_query("INSERT members.Payments (email, submitted, amount) VALUES($email2, NOW(), $amount2)", $link)
	or die('mysql_query INSERT Payments error');
mysql_close($link);
unset($link);

$body = "Welcome!

You can now open the door by going to http://door.xinchejian.com/
Username: $email
Password: $password

Note that your access will be revoked if no payment was made.

-- the script that sends out these emails";
mail($email, 'Welcome to Xinchejian', $body, 'From: no-reply@lunesu.com');

mail('staff@xinchejian.com', "New member: $email", '-- the script that sends out these emails', 'From: no-reply@lunesu.com');

header('HTTP/1.1 303 See Other');
header("Location: $ROOT/welcome.html");

