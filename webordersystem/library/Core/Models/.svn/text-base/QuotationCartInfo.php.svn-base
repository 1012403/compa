<?php
/**
 * 
 * @author nhanlt
 *
 */
class Core_Models_QuotationCartInfo extends Core_Models_Domain {
	private $userId;
	private $productId;
	private $quantity;
	
	/**
	 *
	 * @param string $data
	 */
	public function __construct($data = null) {
		parent::__construct($data);
		if ($data != null) {
			if ($data instanceof Zend_Db_Table_Row_Abstract
			|| is_array($data) == TRUE) {
				$this->userId		= $this->getData($data, 'user_id');
				$this->productId	= $this->getData($data, 'product_id');
				$this->quantity		= $this->getData($data, 'quantity');
			}
		}
	}
	
	/**
	 * (non-PHPdoc)
	 * @see Core_Models_Domain::toArray()
	 */
	public function toArray() {
		$arr = parent::toArray();
		$arr["user_id"]		= $this->userId;
		$arr["product_id"]	= $this->productId;
		$arr["quantity"]	= $this->quantity;
		return $arr;
	}
	
	/**
	 * getUserId
	 */
	public function getUserId(){
		return $this->userId;
	}
	
	/**
	 * 
	 * @param unknown $userId
	 */
	public function setUserId($userId){
		$this->userId = $userId;
	}
	
	/**
	 * getProductId
	 */
	public function getProductId(){
		return $this->productId;
	}
	
	/**
	 * setProductId
	 * @param unknown $productId
	 */
	public function setProductId($productId){
		$this->productId = $productId;
	}
	
	/**
	 * getQuantity
	 */
	public function getQuantity(){
		return $this->quantity;
	}
	
	/**
	 * setQuantity
	 * @param unknown $quantity
	 */
	public function setQuantity($quantity){
		$this->quantity = $quantity;
	}
	
	/**
	 * increaseQuantity
	 * @param unknown $num
	 */
	public function increaseQuantity($num) {
		$this->setQuantity($this->getQuantity() + $num);
	}
	
	/**
	 * increaseQuantity
	 * @param unknown $num
	 */
	public function decreaseQuantity($num) {
		$this->setQuantity($this->getQuantity() - $num);
	}

}
