<?php
class Zend_View_Helper_EchoDate {
	function echoDate($value, $format="Y年m月d日") {
		return date($format,strtotime($value));
	}
}
