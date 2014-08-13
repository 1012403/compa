<?php
class Zend_View_Helper_EchoShippingMethod {
	function echoShippingMethod($shippingValue) {
		return Core_Util_Helper::getShippingMethodName($shippingValue);
	}
}