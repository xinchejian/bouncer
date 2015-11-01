<?php
require_once 'mailer.php';

$link = new SQLite3('/var/bouncer/members.db')
	or mail_and_die('SQLite3 ctor error', __FILE__);
