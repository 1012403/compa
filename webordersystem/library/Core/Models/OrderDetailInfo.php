<?php
class Core_Models_OrderDetailInfo extends Core_Models_MasterModel {
	private  $orderId;
	private  $detailNo;
	private  $productId ;
	private  $quantity ;
	private  $price;
	private  $priceIncludingTax;
	private  $totalPrice;
	private  $tax;
	private  $shippingFee;
	private  $itemName;
	
	// extra properties
	/* @var $product Core_Models_MstProduct */
	private $product;
	
	public function __construct($data = null) {
		parent::__construct($data);
		if ($data != null) {
			if ($data instanceof Zend_Db_Table_Row_Abstract
			|| is_array($data) == TRUE) {
				$this->orderId		= $this->getData($data, 'order_id');
				$this->detailNo		= $this->getData($data, 'detail_no');
				$this->productId	= $this->getData($data, 'product_id');
				$this->quantity		= $this->getData($data, 'quantity');
				$this->price		= $this->getData($data, 'price');
				$this->priceIncludingTax	= $this->getData($data, 'price_including_tax');
				$this->totalPrice	= $this->getData($data, 'total_price');
				$this->tax			= $this->getData($data, 'tax');
				$this->shippingFee	= $this->getData($data, 'shipping_fee');
				$this->itemName	= $this->getData($data, 'item_name');
				
				if ($this->getData($data, 'product_name') != null) {
					$product = new Core_Models_MstProduct($data);
					$this->product = $product;
				}
			}
		}
	}
	/**
	 * (non-PHPdoc)
	 * @see Core_Models_Domain::toArray()
	 */
	public function toArray() {
		$arr = parent::toArray();
		$arr["order_id"] 	= $this->orderId;
		$arr["detail_no"]	= $this->detailNo;
		$arr["product_id"]	= $this->productId;
		$arr["quantity"] 	= $this->quantity;
		$arr["price"] 		= $this->price;
		$arr["price_including_tax"] = $this->priceIncludingTax;
		$arr["total_price"] = $this->totalPrice;
		$arr["tax"] 		= $this->tax;
		$arr["shipping_fee"]= $this->shippingFee;
		return $arr;
	}
	/**
	 * 
	 * @return getOrderId
	 */
	public function getOrderId(){
		return $this->orderId;
	}
	/**
	 * 
	 * @param setOrderId
	 */
	public function setOrderId($orderId){
		$this->orderId = $orderId;
	}
	/**
	 * 
	 * @return getDetailNo
	 */
	public function getDetailNo(){
		return $this->detailNo;
	}

	public function setDetailNo($detailNo){
		$this->detailNo = $detailNo;
	}
	/**
	 * 
	 * @return getProductId
	 */
	public function getProductId(){
		return $this->productId;
	}
	/**
	 * 
	 * @paramsetProductId
	 */
	public function setProductId($productId){
		$this->productId = $productId;
	}
	/**
	 * 
	 * @return getQuantity
	 */
	public function getQuantity(){
		return $this->quantity;
	}
	/**
	 * 
	 * @param setQuantity
	 */
	public function setQuantity($quantity){
		$this->quantity = $quantity;
	}
	/**
	 * 
	 * @return getPrice
	 */
	public function getPrice(){
		return $this->price;
	}
	/**
	 * 
	 * @param setPrice
	 */
	public function setPrice($price){
		$this->price = $price;
	}
	
	/**
	 * 
	 * @return getPriceIncludingTax
	 */
	public function getPriceIncludingTax(){
		return $this->priceIncludingTax;
	}
	/**
	 * 
	 * @param setPriceIncludingTax
	 */
	public function setPriceIncludingTax($priceIncludingTax){
		$this->priceIncludingTax = $priceIncludingTax;
	}
	
	/**
	 * 
	 * @return getTax
	 */
	public function getTax(){
		return $this->tax;
	}
	/**
	 * 
	 * @param setTax
	 */
	public function setTax($tax){
		$this->tax = $tax;
	}

	/**
	 * 
	 * @return getShippingFee
	 */
	public function getShippingFee(){
		return $this->shippingFee;
	}
	/**
	 * 
	 * @param setShippingFee
	 */
	public function setShippingFee($shippingFee){
		$this->shippingFee = $shippingFee;
	}
	
	/**
	 * Core_Models_MstProduct
	 */
	public function getProduct(){
		return $this->product;
	}
	
	public function setProduct($product){
		$this->product = $product;
	}
	
	public function getTotalPrice(){
		return $this->totalPrice;
	}
	
	public function setTotalPrice($totalPrice){
		$this->totalPrice = $totalPrice;
	}
	public function getItemName(){
		return $this->itemName;
	}
	public function setItemName($itemName){
		$this->itemName = $itemName;
	}

}
