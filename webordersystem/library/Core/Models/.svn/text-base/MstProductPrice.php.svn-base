<?php
class Core_Models_MstProductPrice extends Core_Models_Domain {
    private $applyStartDate;
    private $productId;
    private $price;
    private $priceIncludingTax;
    private $tax;
    private $priceConditionClass;
    private $quantityRestriction;

    public function __construct($data = null) {
        if ($data != null) {
          if ($data instanceof Zend_Db_Table_Row_Abstract
          || is_array($data) == TRUE) {
            $this->applyStartDate	= $this->getData($data, 'apply_start_date');
            $this->productId	= $this->getData($data, 'product_id');
            $this->price = $this->getData($data, 'price');
            $this->priceIncludingTax	= $this->getData($data, 'price_including_tax');
            $this->tax	= $this->getData($data, 'tax');
            $this->priceConditionClass	= $this->getData($data, 'price_condition_class');
            $this->quantityRestriction	= $this->getData($data, 'quantity_restriction');
          }
        }
    }

    public function toArray() {
        $arr = parent::toArray();
        $arr["apply_start_date"] = $this->applyStartDate;
        $arr["product_id"] 	= $this->productId;
        $arr["price"] = $this->price;
        $arr["price_including_tax"]	= $this->priceIncludingTax;
        $arr["tax"] = $this->tax;
        $arr["price_condition_class"] = $this->priceConditionClass;
        $arr["quantity_restriction"] = $this->quantityRestriction;

        return $arr;
    }

    public function getApplyStartDate() {
        return $this->applyStartDate;
    }

    public function getProductId() {
        return $this->productId;
    }

    public function getPrice() {
        return $this->price;
    }

    public function getPriceIncludingTax() {
        return $this->priceIncludingTax;
    }

    public function getTax() {
        return $this->tax;
    }

    public function setApplyStartDate($applyStartDate) {
        $this->applyStartDate = $applyStartDate;
    }

    public function setProductId($productId) {
        $this->productId = $productId;
    }

    public function setPrice($price) {
        $this->price = $price;
    }

    public function setPriceIncludingTax($priceIncludingTax) {
        $this->priceIncludingTax = $priceIncludingTax;
    }

    public function setTax($tax) {
        $this->tax = $tax;
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