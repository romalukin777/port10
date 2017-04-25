<?php
	class Model_Login extends Model
	{
		public function login($login, $password)
		{
			$msgs = array();
			if(empty($login))
				$msgs[] = 'login is missing';
			if(empty($password))
				$msgs[] = 'password is missing';
			if(!count($msgs))
			{
				if(strcmp(Model::$config['us_login'], $login) === 0 && strcmp(Model::$config['us_password'], $password) === 0)
				{
					$_SESSION = array();
					$_SESSION['logged'] = true;
					$_SESSION['hash'] = md5("salt)keckeboot$password");
					$_SESSION['ipaddr'] = md5($_SERVER['REMOTE_ADDR']);
					$msgs[] = 'success';
				}
				else $msgs[] = 'login or password is invalid';
			}
			return ucwords(implode('<br> ', $msgs));
		}
	}
?>