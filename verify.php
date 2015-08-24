<?php
require('inc/common.php');
require('inc/mailer.php');

$paymentid = (int)$_GET['id'];
$ok = (int)$_GET['ok'];
$email = urldecode($_GET['email']);

function mail_and_die($m)
{
  mailer('it@xinchejian.com', 'Error in '.__FILE__, $m);
  die($m);
}

// add SetEnv MYSQL_PASSWORD "blah" to this site's Apache conf
$link = mysql_connect('localhost', 'webuser', getenv('MYSQL_PASSWORD'))
	or mail_and_die('mysql_connect error');

$email2 = '"'.mysql_real_escape_string($email, $link).'"';

$result = mysql_query("SELECT email,amount FROM members.Payments WHERE id = $paymentid;", $link)
	or die('mysql_query SELECT error');

if ($row = mysql_fetch_assoc($result))
	$amount = $row['amount'];
if ($amount == '100')
	$months = 1;
else if ($amount == '450')
	$months = 6;
else if ($amount == '900')
	$months = 12;
else if ($amount == '5000')
	$months = 12;
else
	mail_and_die('wrong amount');

mysql_query("UPDATE members.Payments SET verified = $ok WHERE id = $paymentid", $link)
	or mail_and_die('mysql_query UPDATE Payments error');

if ($ok) {
	mysql_query("UPDATE members.Users SET paid_verified = (SELECT submitted FROM members.Payments WHERE id = $paymentid) + INTERNAL $months MONTH WHERE email = $email2", $link)
		or mail_and_die('mysql_query UPDATE Users error');
}
else {
	mysql_query("UPDATE members.Users SET paid = paid - INTERVAL $months MONTH WHERE email = $email2", $link)
		or mail_and_die('mysql_query UPDATE Users error');
	//mailer($email, $subject, $body);
}

mysql_close($link);
unset($link);
