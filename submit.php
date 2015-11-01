<?php
require 'inc/common.php';
require 'inc/mailer.php';


if ($_SERVER['REQUEST_METHOD'] != 'POST') {
	header('Location: submit.html', true, 303);
	die('must be POST');
}


$amount = (int)$_POST['amount'];
$email = trim($_POST['email']);
if (!filter_var($email, FILTER_VALIDATE_EMAIL))
	mail_and_die('invalid email', __FILE__);
if ($amount == '100')
	$months = 1;
else if ($amount == '450')
	$months = 6;
else if ($amount == '900')
	$months = 12;
else if ($amount == '5000')
	$months = 12;
else
	mail_and_die('wrong amount', __FILE__);

// TODO: generate this salt
$salt = 'salT';
// Friendlier pincode instead of password
$crc = crc32($salt.strtoupper($email)) & 0x7FFFFFFF;//remove sign
$password = sprintf("%06u", $crc % 1000000);

require 'inc/db.php';

$email2 = $link->escapeString($email);
$password2 = $link->escapeString($password);
$salt2 = $link->escapeString($salt);

$link->exec("INSERT OR IGNORE INTO Users (email,since) VALUES('$email2',DATETIME('now'))")
	or mail_and_die('link->exec INSERT Users error', __FILE__);
$isnew = $link->changes() == 1;

$link->exec("INSERT INTO Payments (email, submitted, amount) VALUES('$email2', DATETIME('now'), $amount)")
	or mail_and_die('link->exec INSERT Payments error', __FILE__);

// Give new members the benefit of the doubt (trust, but verify):
// FIXME: might fail because of unique password (change salt)
$link->exec("UPDATE Users SET paid = DATE(MAX(IFNULL(paid,0), DATE('now')),'+$months MONTH'), salt = '$salt2', password = '$password2' WHERE email = '$email2'")
	or mail_and_die('link->exec UPDATE error', __FILE__);
if ($link->changes() != 1)
	mail_and_die('link->changes should be 1', __FILE__);

$link->close();
unset($link);

$subject = 'Welcome to Xinchejian 欢迎加入新车间';
$body = "Welcome! 欢迎！

You can now open the door by going to http://bouncer/
PIN: $password

Note that your access will be revoked if no payment was made.

-- the script that sends out these emails";
mailer($email, $subject, $body);

if ($isnew)
	$neworold = "New";
else
	$neworold = "Old";
mailer('finances@xinchejian.com', "$neworold member: $email, paid $amount for $months month(s).", '-- '.__FILE__);

header('Location: welcome.html', true, 303);

