<?php
	function isLogged()
	{
		if(!isset($_SESSION['logged']) && !isset($_SESSION['hash']) && !isset($_SESSION['ipaddr']) || 
		    strcmp(md5('salt)keckeboot'.Model::$config['us_password']), $_SESSION['hash']) !== 0 || !$_SESSION['logged'] ||
			strcmp($_SESSION['ipaddr'], md5($_SERVER['REMOTE_ADDR'])) !== 0) return false;
		return true;
	}
	class Model
	{
		static public $config = array();
	}
	Model::$config = parse_ini_file('includes/config.ini');
?>