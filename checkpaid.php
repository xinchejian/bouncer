<?php
// Add this to a daily cronjob using curl or somesuch:
// 0 6 * * * /usr/bin/curl http://localhost/checkpaid.php

require 'inc/mailer.php';

function mail_and_die($m)
{
  mailer('it@xinchejian.com', 'Error in '.__FILE__, $m);
  die($m);
}


$subject = 'Xinchejian membership reminder 新车间会员资格到期提醒';
$body = 'Dear Xinchejian member,

Your membership with Xinchejian is about to expire today. Please renew your membership to be able to get into the space.

Thanks for your continuous support!


亲爱的新车间会员，

您在新车间的会员资格即将于今日到期。为了正常使用新车间的空间设施，请为您的会员资格续费！

谢谢您一如既往的支持！

-- the script that sends out these emails';

// add SetEnv MYSQL_PASSWORD "blah" to this site's Apache conf
$link = mysql_connect('localhost', 'webuser', getenv('MYSQL_PASSWORD'))
	or mail_and_die('mysql_connect error');

$result = mysql_query('SELECT email FROM members.Users WHERE paid = CURDATE() OR paid_verified = CURDATE()', $link)
	or die('mysql_query SELECT error');

while ($row = mysql_fetch_assoc($result)) {
	mailer($row['email'], $subject, $body);
}

mysql_close($link);
unset($link);

