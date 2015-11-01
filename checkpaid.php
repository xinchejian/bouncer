<?php
// Add this to a daily cronjob using curl or somesuch:
// 0 6 * * * /usr/bin/curl http://localhost/checkpaid.php

require 'inc/mailer.php';
require 'inc/db.php';


$subject = 'Xinchejian membership reminder 新车间会员资格到期提醒';
$body = 'Dear Xinchejian member,

Your membership with Xinchejian is about to expire today. Please renew your membership to be able to get into the space.

Thanks for your continuous support!


亲爱的新车间会员，

您在新车间的会员资格即将于今日到期。为了正常使用新车间的空间设施，请为您的会员资格续费！

谢谢您一如既往的支持！

-- the script that sends out these emails';

$result = $link->query("SELECT email FROM Users WHERE paid = DATE('now') OR paid_verified = DATE('now')")
	or die('link->query SELECT error');

while ($row = $result->fetchArray()) {
	mailer($row['email'], $subject, $body);
}

$link->close();
unset($link);

