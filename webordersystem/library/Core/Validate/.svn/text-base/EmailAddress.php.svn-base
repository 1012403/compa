<?php

/**
 * Override Zend_Validate_EmailAddress so that only one error message shows when there is a problem
 */
class Core_Validate_EmailAddress extends Zend_Validate_EmailAddress {
	private $_error_msg = 'は無効です。';
	/**
	 * Returns true if and only if $value is a valid email address
	 * according to RFC2822
	 * 
	 * @param string $value        	
	 * @return boolean
	 */
	public function isValid($value) {
		$response = parent::isValid ( $value );
		if (! $response) {
			$this->_messages = array (
					self::INVALID => $this->_error_msg 
			);
		}
		return $response;
	}
	public function get_error_msg() {
		return $this->_error_msg;
	}
	public function set_error_msg($_error_msg) {
		$this->_error_msg = $_error_msg;
	}
}