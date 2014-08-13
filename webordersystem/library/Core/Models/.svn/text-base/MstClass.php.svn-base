<?php

class Core_Models_MstClass extends Core_Models_MasterModel {
	public static $TOTAL_FIELD = 10;
	private $id;
	private $itemType;
	private $itemCd;
	private $itemName;
	private $itemOrder;
	private $displayFlg;
	private $note1;
	private $note2;
	private $note3;
	private $note4;
	private $note5;

	public function __construct($data = null) {
		parent::__construct($data);
		if ($data != null) {
			if ($data instanceof Zend_Db_Table_Row_Abstract
			|| is_array($data) == TRUE) {
				$this->id				= $this->getData($data, 'id');
				$this->itemType			= $this->getData($data, 'item_type');
				$this->itemCd			= $this->getData($data, 'item_cd');
				$this->itemName			= $this->getData($data, 'item_name');
				$this->itemOrder		= $this->getData($data, 'item_order');
				$this->displayFlg		= $this->getData($data, 'display_flg');
				$this->note1			= $this->getData($data, 'note1');
				$this->note2			= $this->getData($data, 'note2');
				$this->note3			= $this->getData($data, 'note3');
				$this->note4			= $this->getData($data, 'note4');
				$this->note5			= $this->getData($data, 'note5');
			}
		}
	}

	public function toArray() {
		$arr = parent::toArray();
		$arr["id"]	= $this->id;
		$arr["item_type"]	= $this->itemType;
		$arr["item_cd"]	= $this->itemCd;
		$arr["item_name"]	= $this->itemName;
		$arr["item_order"]	= $this->itemOrder;
		$arr["display_flg"]	= $this->displayFlg;
		$arr["note1"]	= $this->note1;
		$arr["note2"]	= $this->note2;
		$arr["note3"]	= $this->note3;
		$arr["note4"]	= $this->note4;
		$arr["note5"]	= $this->note5;
		return $arr;
	}
	public function getId(){
		return $this->id;
	}
	
	public function setId($id){
		$this->id = $id;
	}
	
	public function getItemType(){
		return $this->itemType;
	}
	
	public function setItemType($itemType){
		$this->itemType = $itemType;
	}
	
	public function getItemCd(){
		return $this->itemCd;
	}
	
	public function setItemCd($itemCd){
		$this->itemCd = $itemCd;
	}
	
	public function getItemName(){
		return $this->itemName;
	}
	
	public function setItemName($itemName){
		$this->itemName = $itemName;
	}
	
	public function getItemOrder(){
		return $this->itemOrder;
	}
	
	public function setItemOrder($itemOrder){
		$this->itemOrder = $itemOrder;
	}
	
	public function getDisplayFlg(){
		return $this->displayFlg;
	}
	
	public function setDisplayFlg($displayFlg){
		$this->displayFlg = $displayFlg;
	}
	
	public function getNote1(){
		return $this->note1;
	}
	
	public function setNote1($note1){
		$this->note1 = $note1;
	}
	
	public function getNote2(){
		return $this->note2;
	}
	
	public function setNote2($note2){
		$this->note2 = $note2;
	}
	
	public function getNote3(){
		return $this->note3;
	}
	
	public function setNote3($note3){
		$this->note3 = $note3;
	}
	
	public function getNote4(){
		return $this->note4;
	}
	
	public function setNote4($note4){
		$this->note4 = $note4;
	}
	
	public function getNote5(){
		return $this->note5;
	}
	
	public function setNote5($note5){
		$this->note5 = $note5;
	}
	
	/**
	 * 
	 * @return array
	 */
	public static function getHeaderCsv() {
		$arrHeader = array();
		//$arrHeader[] = "項目ID";
		$arrHeader[] = "項目種別";
		$arrHeader[] = "項目コード";
		$arrHeader[] = "項目名";
		$arrHeader[] = "並び順";
		$arrHeader[] = "表示ﾌﾗｸﾞ";
		$arrHeader[] = "備考１";
		$arrHeader[] = "備考２";
		$arrHeader[] = "備考３";
		$arrHeader[] = "備考４";
		$arrHeader[] = "備考５";
		return $arrHeader;
	}
	
	public function toCsvData() {
		$arrData = array();
		//$arrData[] = $this->getId();
		$arrData[] = $this->getItemType();
		$arrData[] = $this->getItemCd();
		$arrData[] = $this->getItemName();
		$arrData[] = $this->getItemOrder();
		$arrData[] = $this->getDisplayFlg();
		$arrData[] = $this->getNote1();
		$arrData[] = $this->getNote2();
		$arrData[] = $this->getNote3();
		$arrData[] = $this->getNote4();
		$arrData[] = $this->getNote5();
		return $arrData;
	}
	
	public static function createMstClassFromCsvRow($row) {
		$csvAgent = new Core_Models_CsvAgent();
		$arr = $csvAgent->convertCsvRowToArray($row);
		if (count($arr) == self::$TOTAL_FIELD) {
			$mstClass = new Core_Models_MstClass();
			//$mstClass->setId($arr[0]);
			$mstClass->setItemType($arr[0]);
			$mstClass->setItemCd($arr[1]);
			$mstClass->setItemName($arr[2]);
			$mstClass->setItemOrder($arr[3]);
			$mstClass->setDisplayFlg($arr[4]);
			$mstClass->setNote1($arr[5]);
			$mstClass->setNote2($arr[6]);
			$mstClass->setNote3($arr[7]);
			$mstClass->setNote4($arr[8]);
			$mstClass->setNote5($arr[9]);
			return $mstClass;
		} else {
			throw new Exception("Csv field not match to MstClass Obj");
		}
	}
}