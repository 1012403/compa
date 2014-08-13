<?php
/**
 * 
 * @author nhanlt
 *
 */
class Zend_View_Helper_formatNumber {
	function formatNumber($num, $de = 0) {
		return Core_Util_Formatter::formateNumber($num, $de);
	}
}
