<?php
class Core_Models_CsvAgent {
	private $enclose = "\"";
	private $sep = ",";
	
	public  function __construct($enclose = "\"", $sep = "," ) {
		$this->enclose = $enclose;
		$this->sep = $sep;
	}
	public function getEnclose(){
		return $this->enclose;
	}
	
	public function setEnclose($enclose){
		$this->enclose = $enclose;
	}
	
	public function getSep(){
		return $this->sep;
	}
	
	public function setSep($sep){
		$this->sep = $sep;
	}
	
	public function createLineString($arrString, $hasNewline = true) {
		if (!is_array($arrString)) {
			return null;
		} else {
			$header = "";
			foreach ($arrString as $key => $value) {
				$value = $this->encloseData($value);
				$header = $this->appendTo($header, $value);
			}
			if ($hasNewline) {
				$header .= "\r\n";
			}
			return $header;
		}
	}
	
	private function encloseData($data) {
		return $this->enclose . $data . $this->enclose;
	}
	
	private function appendTo($data, $object) {
		if (Core_Util_Helper::isEmpty($data)) {
			return $object;
		} else {
			return $data .= $this->sep . $object;
		}
	}
	
	public function removeEnclose($str) {
		$str = str_replace($this->enclose, "", $str);
		return $str;
	}
	
	public function convertCsvRowToArray($row) {
		$arr = explode($this->sep, $row);
		foreach ($arr as $key => $str) {
			$str = $this->removeEnclose($str);
			$arr[$key] = $str;
		}
		return $arr;
	}
}