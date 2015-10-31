<?php
require 'inc/db.php';

$link->exec(file_get_contents('db.sql'))
        or die('link->exec failed');
$link->close();

header('Location: index.php', true, 303);
