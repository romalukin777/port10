<?php
	class Data
	{
		public static function encrypt($inputString, $keyString = null)
		{
			if(empty($inputString)) return '';
			$inputData = unpack('C*', $inputString);
			$cryptKey = array(0, 0);
			if(!empty($keyString))
			{
				$cryptKey = unpack('C*', $keyString);
			}
			$cLength = count($inputData);
			$cryptKey = array_splice($cryptKey, 0, $cLength);
			$kLength = count($cryptKey);
			$cryptedData = array();
			$kIdx = 0;
			for ($i = 0; $i < $cLength; $i++)
			{
				if($kIdx >= $kLength) $kIdx = 0;
				$intByte = $inputData[$i+1] + $i + $cryptKey[$kIdx];
				if($intByte > 255) $intByte -= 256;
				else if($intByte < 0) $intByte += 256;
				$cryptedData[] = $intByte;
				$kIdx++;
			}
			$cryptedData = array_reverse($cryptedData);
			$chars = '';
			for($i=0;$i<$cLength;$i++) $chars .= chr($cryptedData[$i]);
			$chars = base64_encode($chars);
			$chars = str_replace('+', ')', $chars);
			$chars = str_replace('=', '(', $chars);
			$chars = str_replace('/', '-', $chars);
			return $chars;
		}
		public static function decrypt($inputString, $keyString = null)
		{
			if(empty($inputString)) return '';
			$inputString = str_replace(')', '+',$inputString);
			$inputString = str_replace('(', '=',$inputString);
			$inputString = str_replace('-', '/',$inputString);
			$inputData = array_reverse(unpack('C*', base64_decode($inputString)));
			$cryptKey = array(0, 0);
			if(!empty($keyString))
			{
				$cryptKey = unpack('C*', $keyString);
			}
			$cLength = count($inputData);
			$cryptKey = array_splice($cryptKey, 0, $cLength);
			$kLength = count($cryptKey);
			$decryptedData = array();
			$kIdx = 0;
			for ($i = 0; $i < $cLength; $i++)
			{
				if($kIdx >= $kLength) $kIdx = 0;
				$intByte = $inputData[$i] - $i - $cryptKey[$kIdx];
				if($intByte > 255) $intByte -= 256;
				else if($intByte < 0) $intByte += 256;
				$decryptedData[] = $intByte;
				$kIdx++;
			}
			$chars = '';
			for($i=0;$i<$cLength;$i++) $chars .= chr($decryptedData[$i]);
			return $chars;
		}
	}
?>