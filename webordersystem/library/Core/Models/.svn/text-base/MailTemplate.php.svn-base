<?php

class Core_Models_MailTemplate extends Core_Models_Domain {
	private $mailId;
	private $itemCd;
	private $name;
	private $title;
	private $header;
	private $footer;
	private $flagTemp;
	
	public function __construct($data = null) {
		if ($data != null) {
			if ($data instanceof Zend_Db_Table_Row_Abstract || is_array ( $data ) == TRUE) {
				$this->mailId = $this->getData ( $data, 'id' );
				$this->itemCd = $this->getData ( $data, 'class' );
				$this->name = $this->getData ( $data, 'name' );
				$this->title = $this->getData ( $data, 'title' );
				$this->header = $this->getData ( $data, 'header' );
				$this->footer = $this->getData ( $data, 'footer' );
				$this->flagTemp = $this->getData ( $data, 'apply_flg' );
			}
		}
	}
	
	public function toArray() {
		$arr = parent::toArray ();
		$arr ["id"] = $this->mailId;
		$arr ["class"] = $this->itemCd;
		$arr ["name"] = $this->name;
		$arr ["title"] = $this->title;
		$arr ["header"] = $this->header;
		$arr ["footer"] = $this->footer;
		$arr ["apply_flg"] = $this->flag_temp;
		return $arr;
	}
	
	public function getMailId(){
		return $this->mailId;
	}
	
	public function setMailId($mailId){
		$this->mailId = $mailId;
	}
	
	public function getItemCd(){
		return $this->itemCd;
	}
	
	public function setItemCd($itemCd){
		$this->itemCd = $itemCd;
	}
	
	public function getName(){
		return $this->name;
	}
	
	public function setName($name){
		$this->name = $name;
	}
	
	public function getTitle(){
		return $this->title;
	}
	
	public function setTitle($title){
		$this->title = $title;
	}
	
	public function getHeader(){
		return $this->header;
	}
	
	public function setHeader($header){
		$this->header = $header;
	}
	
	public function getFooter(){
		return $this->footer;
	}
	
	public function setFooter($footer){
		$this->footer = $footer;
	}
	
	public function getFlagTemp(){
		return $this->flagTemp;
	}
	
	public function setFlagTemp($flagTemp){
		$this->flagTemp = $flagTemp;
	}
}
