<?php

require_once("PHPMailer/PHPMailerAutoload.php");
//include("class.smtp.php"); // optional, gets called from within class.phpmailer.php if not already loaded

$mail             = new PHPMailer();

$mail->IsSMTP();
$mail->SMTPAuth   = true;                  // enable SMTP authentication
$mail->SMTPSecure = 'ssl';                 // sets the prefix to the servier
$mail->CharSet    = 'UTF-8';
$mail->Host       = 'smtp.sendgrid.net';   // sets GMAIL as the SMTP server
$mail->Port       = 465;                   // set the SMTP port for the GMAIL server


$mail->Username   = 'xinchejian';          // GMAIL/sendgrid username
// add SetEnv SMTP_PASSWORD "blah" to this site's Apache conf
$mail->Password   = getenv('SMTP_PASSWORD');              // GMAIL/sendgrid password

$mail->From       = 'bouncer@xinchejian.com';
$mail->FromName   = 'Xinchejian Bouncer';

$mail->WordWrap   = 60; // set word wrap

function mailer($mail_to, $subject, $body)
{
	global $mail;

	$mail->Subject = $subject;
	$mail->Body = $body;
	$mail->ClearAddresses();
	$mail->AddAddress($mail_to);
	$mail->Send()
		or die('Failed to send email');
}

function mail_and_die($subject, $body)
{
  mailer('it@xinchejian.com', $subject, $body);
  die($subject."\n".$body);
}
