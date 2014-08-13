<?php

class Core_Models_FeaturedProduct extends Core_Models_Domain {
	private $userId;
	private $productId;

	public function __construct($data = null) {
		if ($data != null) {
			if ($data instanceof Zend_Db_Table_Row_Abstract
			|| is_array($data) == TRUE) {
				$this->userId		= $this->getData($data, 'user_id');
				$this->productId	= $this->getData($data, 'product_id');
			}
		}
	}

	public function toArray() {
		$arr = parent::toArray();
		$arr["user_id"]	= $this->userId;
		$arr["product_id"]	= $this->productId;
		return $arr;
	}
	
	public function getUserId(){
		return $this->userId;
	}
	
	public function setUserId($userId){
		$this->userId = $userId;
	}
	
	public function getProductId(){
		return $this->productId;
	}
	
	public function setProductId($productId){
		$this->productId = $productId;
	}
}