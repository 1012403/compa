<?php
class Core_Models_MstProduct extends Core_Models_MasterModel {
  private $productId;
  private $productName;
  private $productNo;
  private $productUnit;
  private $productBrief;
  private $barcode;
  private $shippingFee;
  private $shippingClass;
  private $shippingDisplayFlag;
  private $stockClass;
  private $stockDisplayFlag;
  private $stockQty;
  private $imagePath;

  private $orderQuantity;
  private $price;
  private $priceIncludingTax;
  private $tax;
  private $magnificationPoint;
  private $makerProductNo;
  private $supplierCode;
  private $priceConditionClass;
  private $quantityRestriction;

  public function __construct($data = null) {
  	parent::__construct($data);
    if ($data != null) {
      if ($data instanceof Zend_Db_Table_Row_Abstract
      || is_array($data) == TRUE) {
        $this->productId	= $this->getData($data, 'product_id');
        $this->productName	= $this->getData($data, 'product_name');
        $this->productNo	= $this->getData($data, 'product_no');
        $this->productUnit	= $this->getData($data, 'product_unit');
        $this->productBrief	= $this->getData($data, 'product_brief');
        $this->stockQty		= $this->getData($data, 'stock_qty');
        $this->barcode		= $this->getData($data, 'barcode');
        $this->shippingFee	= $this->getData($data, 'shipping_fee');
        $this->imagePath	= $this->getData($data, 'image_path');
        $this->shippingClass		= $this->getData($data, 'shipping_class');
        $this->shippingDisplayFlag	= $this->getData($data, 'shipping_display_flag');
        $this->stockClass			= $this->getData($data, 'stock_class');
        $this->stockDisplayFlag	= $this->getData($data, 'stock_display_flag');

        $this->orderQuantity		= $this->getData($data, 'order_quantity');
        $this->price				= $this->getData($data, 'price');
        $this->priceIncludingTax	= $this->getData($data, 'price_including_tax');
        $this->tax					= $this->getData($data, 'tax');
        $this->magnificationPoint	= $this->getData($data, 'magnification_point');
        $this->makerProductNo	= $this->getData($data, 'maker_product_no');
        $this->supplierCode	= $this->getData($data, 'supplier_code');
        $this->priceConditionClass	= $this->getData($data, 'price_condition_class');
        $this->quantityRestriction	= $this->getData($data, 'quantity_restriction');
      }
    }
  }

  public function toArray() {
    $arr = parent::toArray();
    $arr["product_id"] 		= $this->productId;
    $arr["product_name"] 	= $this->productName;
    $arr["product_no"] 		= $this->productNo;
    $arr["product_unit"]	= $this->productUnit;
    $arr["product_brief"] 	= $this->productBrief;
    $arr["stock_qty"] 		= $this->stockQty;
    $arr["barcode"] 		= $this->barcode;
    $arr["shipping_fee"]	= $this->shippingFee;
    $arr["image_path"] 		= $this->imagePath;
    $arr["shipping_class"] 	= $this->shippingClass;
    $arr["shipping_display_flag"] 	= $this->shippingDisplayFlag;
    $arr["stock_class"] 			= $this->stockClass;
    $arr["stock_display_flag"] 	= $this->stockDisplayFlag;
    $arr["maker_product_no"] 	= $this->makerProductNo;
    $arr["supplier_code"] 	= $this->supplierCode;
    /*
    $arr["price_condition_class"] 	= $this->priceConditionClass;
    $arr["quantity_restriction"] 	= $this->quantityRestriction;
    */

    return $arr;
  }

  public function getProductId(){
  	return $this->productId;
  }

  public function setProductId($productId){
  	$this->productId = $productId;
  }

  public function getProductName(){
  	return $this->productName;
  }

  public function setProductName($productName){
  	$this->productName = $productName;
  }

  public function getProductNo(){
  	return $this->productNo;
  }

  public function setProductNo($productNo){
  	$this->productNo = $productNo;
  }

  public function getProductUnit(){
  	return $this->productUnit;
  }

  public function setProductUnit($productUnit){
  	$this->productUnit = $productUnit;
  }

  public function getProductBrief(){
  	return $this->productBrief;
  }

  public function setProductBrief($productBrief){
  	$this->productBrief = $productBrief;
  }

  public function getBarcode(){
  	return $this->barcode;
  }

  public function setBarcode($barcode){
  	$this->barcode = $barcode;
  }

  public function getShippingFee(){
  	return $this->shippingFee;
  }

  public function setShippingFee($shippingFee){
  	$this->shippingFee = $shippingFee;
  }

  public function getShippingClass(){
  	return $this->shippingClass;
  }

  public function setShippingClass($shippingClass){
  	$this->shippingClass = $shippingClass;
  }

  public function getShippingDisplayFlag(){
  	return $this->shippingDisplayFlag;
  }

  public function setShippingDisplayFlag($shippingDisplayFlag){
  	$this->shippingDisplayFlag = $shippingDisplayFlag;
  }

  public function getStockClass(){
  	return $this->stockClass;
  }

  public function setStockClass($stockClass){
  	$this->stockClass = $stockClass;
  }

  public function getStockDisplayFlag(){
  	return $this->stockDisplayFlag;
  }

  public function setStockDisplayFlag($stockDisplayFlag){
  	$this->stockDisplayFlag = $stockDisplayFlag;
  }

  public function getStockQty(){
  	return $this->stockQty;
  }

  public function setStockQty($stockQty){
  	$this->stockQty = $stockQty;
  }

  public function getImagePath(){

  	if (Core_Util_Helper::isEmpty($this->imagePath)) {
  		// default image
  		return Core_Util_Const::NO_IMAGE_PRODUCT;
  	}

  	return $this->imagePath;
  }

  public function setImagePath($imagePath){
  	$this->imagePath = $imagePath;
  }


  public function getOrderQuantity(){
  	return $this->orderQuantity;
  }

  public function setOrder_quantity($orderQuantity){
  	$this->orderQuantity = $orderQuantity;
  }

  public function getPriceIncludingTax(){
  	return $this->priceIncludingTax;
  }

  public function setPriceIncludingTax($priceIncludingTax){
  	$this->priceIncludingTax = $priceIncludingTax;
  }

  public function getPrice(){
  	return $this->price;
  }

  public function setPrice($price){
  	$this->price = $price;
  }

  public function getTax(){
  	return $this->tax;
  }

  public function setTax($tax){
  	$this->tax = $tax;
  }

  public function getMagnificationPoint(){
  	return $this->magnificationPoint;
  }

  public function setMagnificationPoint($magnificationPoint){
  	$this->magnificationPoint = $magnificationPoint;
  }

  public function getTotalPrice() {
		return $this->getPriceIncludingTax() * $this->getOrderQuantity();
  }

  public function getTotalPriceByQuantity($quantity) {
  	return $this->getPriceIncludingTax() * $quantity;
  }

  public function getPoint($quantity) {
  	$pointRate = Core_Util_Helper::getPointRate();
  	$totalProductPrice = $this->getTotalPriceByQuantity($quantity);
  	$pointOfProduct = $totalProductPrice * ($pointRate / 100) * $this->getMagnificationPoint();
  	//Core_Util_LocalLog::writeLog("***");
  	//Core_Util_LocalLog::writeLog("product id = " . $this->getProductId());
  	//Core_Util_LocalLog::writeLog("totalPrice = " . $this->getPriceIncludingTax(). " * " . $quantity . " = " . $totalProductPrice);
  	//Core_Util_LocalLog::writeLog("point of product = " . $totalProductPrice. "(totalprice)"  . " * " .  ($pointRate / 100) . "(pointRate)" . "*" . $this->getMagnificationPoint() . "(magnificationPoint) = " . $pointOfProduct);
  	return $pointOfProduct;
  }
	
  	public function getMakerProductNo(){
		return $this->makerProductNo;
	}

	public function setMakerProductNo($makerProductNo){
		$this->makerProductNo = $makerProductNo;
	}

	public function getSupplierCode(){
		return $this->supplierCode;
	}

	public function setSupplierCode($supplierCode){
		$this->supplierCode = $supplierCode;
	}
	
	public function getPriceConditionClass(){
		return $this->priceConditionClass;
	}

	public function setPriceConditionClass($priceConditionClass){
		$this->priceConditionClass = $priceConditionClass;
	}

	public function getQuantityRestriction(){
		return $this->quantityRestriction;
	}

	public function setQuantityRestriction($quantityRestriction){
		$this->quantityRestriction = $quantityRestriction;
	}

}