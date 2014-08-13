<?php
class Core_Util_LocalLog {
	const FILE_NAME = "local_log.txt";
	public static function writeLog($mess) {
		if (is_array($mess)) {
			$mess = self::arraytoString($mess);
		}
		$myFile = self::FILE_NAME;
		$fh = fopen($myFile, 'a');
		fwrite($fh, $mess);
		fwrite($fh, "\r\n");
		fclose($fh);
	}

	public static function arraytoString($arr) {
		$str = "";
		foreach ($arr as $key => $value) {
			$str .= "[" . $key . "] => " . "{" . $value . "}, ";
		}
		return $str;
	}
}