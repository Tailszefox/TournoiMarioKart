<?php
$config = file('config.txt', FILE_IGNORE_NEW_LINES);
mysql_connect($config[0], $config[1], $config[2]);
mysql_select_db("mariokart");
?>
