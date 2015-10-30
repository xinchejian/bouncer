<!DOCTYPE html>
<html>
<head>
  <title>XCJ payment verification</title>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
  <meta name='viewport' content='width=320'/>
  <script type="text/javascript" language="javascript"><!--
function verify(e, ok, id, email) {
  var xhr = new XMLHttpRequest();
  xhr.open("GET", "verify.php?email="+encodeURIComponent(email)+"&ok="+ok+"&id="+id, true);
  e.disabled = true;
  xhr.onreadystatechange = function() {
    if (this.readyState === 4) {
      e.style.backgroundColor = ok ? "green" : "red";
    }
  };
  xhr.send();  
}
//-->
  </script>
</head>
<body>
 <h1>Unverified Payments</h1>
 <table style="width:100%">
  <thead>
   <tr>
    <th>E-mail</th>
    <th>Submitted</th>
    <th>Amount</th>
    <th>Verified?</th>
   </tr>
  </thead>
  <tbody><?php
require 'inc/common.php';

// add SetEnv MYSQL_PASSWORD "blah" to this site's Apache conf
$link = mysql_connect('localhost', 'webuser', getenv('MYSQL_PASSWORD'))
	or mail_and_die('mysql_connect error');

$result = mysql_query("SELECT id,email,CAST(submitted AS DATE) as submitted,amount FROM members.Payments WHERE verified IS NULL;", $link)
	or die('mysql_query SELECT error');

while ($row = mysql_fetch_assoc($result)) {?>
   <tr>
    <td><?php echo $row['email']; ?></td>
    <td><?php echo $row['submitted']; ?></td>
    <td><?php echo $row['amount']; ?></td>
    <td>
     <input onclick="javascript:verify(this, 1, '<?php echo $row['email']; ?>', <?php echo $row['id']; ?>)" type="button" value="OK"/>
     <input onclick="javascript:verify(this, 0, '<?php echo $row['email']; ?>', <?php echo $row['id']; ?>)" type="button" value="Deny"/>
    </td>
   </tr><?php
}

mysql_close($link);
unset($link);
?></tbody>
 </table>
</body>
</html>
