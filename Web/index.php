<?php
	if(file_exists('includes/config.ini'))
		include_once('application/bootstrap.php');
	else include_once('application/install.php');
?>