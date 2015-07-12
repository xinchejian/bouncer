<?php
require_once('mailer.php');


function mail_and_die($m)
{
  mailer('it@xinchejian.com', 'Error in '.__FILE__, $m);
  die($m);
}

$amount = $_POST['amount'];
$email = trim($_POST['email']);
if ($amount == '100')
	$months = 1;
else if ($amount == '450')
	$months = 6;
else
	mail_and_die('wrong amount');
if (!filter_var($email, FILTER_VALIDATE_EMAIL))
	mail_and_die('invalid email');

// TODO: if salt ever changes, make sure to only mail the password the first time
$salt = 'salT';
$password = sprintf("%08x", crc32($salt.strtoupper($email)));

// add SetEnv MYSQL_PASSWORD "blah" to this site's Apache conf
$link = mysql_connect('localhost', 'webuser', getenv('MYSQL_PASSWORD'))
	or mail_and_die('mysql_connect error');
$email2 = '"'.mysql_real_escape_string($email, $link).'"';
$amount2 = '"'.mysql_real_escape_string($amount, $link).'"';
$password2 = '"'.mysql_real_escape_string($password, $link).'"';
$salt2 = '"'.mysql_real_escape_string($salt, $link).'"';
mysql_query("INSERT IGNORE members.Users (email,since,password,salt) VALUES($email2,NOW(),$password2,$salt2)", $link)
	or mail_and_die('mysql_query INSERT Users error');
mysql_query("INSERT members.Payments (email, submitted, amount) VALUES($email2, NOW(), $amount2)", $link)
	or mail_and_die('mysql_query INSERT Payments error');

// Give new members the benefit of the doubt (trust, but verify):
mysql_query("UPDATE members.Users SET paid = IF(CURDATE()<paid,paid,CURDATE()) + INTERVAL $months MONTH WHERE email = $email2", $link)
	or mail_and_die('mysql_query UPDATE error');
if (mysql_affected_rows($link) != 1)
	mail_and_die('mysql_affected_rows error');
mysql_close($link);
unset($link);

$subject = 'Welcome to Xinchejian 欢迎加入新车间';
$body = "Welcome! 欢迎！

You can now open the door by going to http://bouncer/
Username: $email
Password: $password

Note that your access will be revoked if no payment was made.

-- the script that sends out these emails";
mailer($email, $subject, $body);

mailer('staff@xinchejian.com', "New member: $email, paid $amount for $months month(s).", '-- the script that sends out these emails');

header('HTTP/1.1 303 See Other');
header("Location: /welcome.html");

