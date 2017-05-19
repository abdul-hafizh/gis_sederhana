<?php

	$user	  = 'root';
	$pass	  = '';
	$host	  = 'localhost';
	$database = 'gis_apps';

	$sql = mysql_connect($host, $user, $pass);
	mysql_select_db($database, $sql);
	
	if (!$sql) {
		die('Could not connect : ' . mysql_errno() . mysql_error());
	}
