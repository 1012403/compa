<?php
/**
 * 
 * @author nhanlt
 *
 */
abstract class Core_Models_Domain {
	
	public function __construct($data) {
		
	}
	
	public function __get($name) {
		$ret = null;
		$exists = property_exists($this, $name);
		if ($exists == TRUE) {
			$ret = $this->$name;
		}

		return $ret;
	}

	public function __set($name, $value) {
		$exists = property_exists($this, $name);
		if ($exists == TRUE) {
			$this->$name = $value;
		}
	}

	/**
	 * Convert object to array
	 * @return array:
	 */
	public function toArray() {
		return array();
	}

	protected function getData($data, $index, $default = null) {
		$ret = $default;
		if (isset($data[$index]) == TRUE) {
			$ret = $data[$index];
		}
		return $ret;
	}

	protected function getDbBoolean($value) {
		return $value == TRUE ? 1 : 0;
	}
}
