<?php
header('Cache-Control: no-cache');
header('Pragma: no-cache');
header('Expires: -1');

function find_mac()
{
	$ipAddress = '('.$_SERVER['REMOTE_ADDR'].')';
	// Remember to: chmod +s /usr/sbin/arp
	exec('/usr/sbin/arp -na', $lines);
	foreach($lines as $line)
	{
	   $cols = preg_split('/\s+/', trim($line));
	   if ($cols[1] == $ipAddress)
	   {
		return strtolower($cols[3]);
	   }
	}
	return null;
}

function open_door()
{
	// TEMP: use md5sum over Date, random salt and shared secret
	$req = "pin=0326&action=open";

	$header  = "POST / HTTP/1.1\r\n";      // HTTP POST request
	$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
	$header .= "Content-Length: " . strlen($req) . "\r\n\r\n";

	// Open a socket for the acknowledgement request
	$fp = fsockopen('10.0.10.10', 80, $errno, $errstr, 30);
        if ($fp)
        {
	        fputs($fp, $header . $req);
        	while (!feof($fp))
	        	$res = fgets ($fp, 1024);
        	fclose($fp);

	        header('Location: welcomeback.html', true, 303);

        	exec('/usr/bin/ssh -i /var/rpc_id_rsa root@10.0.10.5 ./add_mac.sh '.$mac);
        }
        else
        {
                $fperr = $errstr;
	        header('Location: dooroffline.html', true, 303);
                require_once 'mailer.php';
		mail_and_die('The door is offline', 'fsockopen returned: '.$fperr);
        }
}
