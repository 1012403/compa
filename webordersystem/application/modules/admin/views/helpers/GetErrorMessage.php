<?php
class Zend_View_Helper_GetErrorMessage {
	function getErrorMessage($form, $nameFormElement,
			$prefix = "", $separator = "") {
		return Core_Util_Helper::getErrorMessage($form, $nameFormElement,
			$prefix, $separator);
	}
}
