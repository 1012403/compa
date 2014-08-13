<?php
class Core_Util_Formatter {
	public static function formateNumber($num, $de = 0) {
		return number_format($num, $de);
	}	
}