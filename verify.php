<?php
require 'inc/common.php';
require 'inc/mailer.php';
require 'inc/db.php';

$paymentid = (int)$_GET['id'];
$ok = (int)$_GET['ok'];
$email = urldecode($_GET['email']);

$email2 = $link->escapeString($email);

$result = $link->query("SELECT email,amount FROM Payments WHERE id = $paymentid;")
	or die('link->query SELECT error');

if ($row = $result->fetchArray())
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
	mail_and_die('wrong amount', __FILE__);

$link->exec("UPDATE Payments SET verified = $ok WHERE id = $paymentid")
	or mail_and_die('link->exec UPDATE Payments error', __FILE__);

if ($ok) {
	$link->exec("UPDATE Users SET paid_verified = (SELECT submitted FROM Payments WHERE id = $paymentid) + INTERNAL $months MONTH WHERE email = '$email2'")
		or mail_and_die('link->exec UPDATE Users error', __FILE__);
}
else {
	$link->exec("UPDATE Users SET paid = DATE(paid, '-$months MONTH') WHERE email = '$email2'")
		or mail_and_die('link->exec UPDATE Users error', __FILE__);
	//mailer($email, $subject, $body);
}

$link->close();
unset($link);
