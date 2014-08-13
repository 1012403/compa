<?php
class Core_Models_QuotationDetailInfo extends Core_Models_MasterModel {
	private  $quotationId ;
	private  $detailNo ;
	private  $productId ;
	private  $quantity ;
	private  $comment ;
	private  $priceIncludingTax;
	private  $validUntilDate;

	public function __construct($data = null) {
		parent::__construct($data);
		if ($data != null) {
			if ($data instanceof Zend_Db_Table_Row_Abstract
			|| is_array($data) == TRUE) {
				$this->quotationId	= $this->getData($data, 'quotation_id');
				$this->detailNo		= $this->getData($data, 'detail_no');
				$this->productId	= $this->getData($data, 'product_id');
				$this->quantity		= $this->getData($data, 'quantity');
				$this->comment		= $this->getData($data, 'comment');
				$this->priceIncludingTax = $this->getData($data, 'price_including_tax');
				$this->validUntilDate = $this->getData($data, 'valid_until_date');
			}
		}
	}

	public function toArray() {
		$arr = parent::toArray();
		$arr["quotation_id"] 		= $this->quotationId;
		$arr["detail_no"] 			= $this->detailNo;
		$arr["product_id"] 			= $this->productId;
		$arr["quantity"] 			= $this->quantity;
		$arr["comment"] 			= $this->comment;
		$arr["price_including_tax"] = $this->priceIncludingTax;
		$arr["price"] 				= $this->price;
		$arr["valid_until_date"] 	= $this->validUntilDate;

		return $arr;
	}

	public function getPriceIncludingTax() {
        return $this->priceIncludingTax;
    }

	public function getValidUntilDate() {
        return $this->validUntilDate;
    }

	public function setPriceIncludingTax($priceIncludingTax) {
        $this->priceIncludingTax = $priceIncludingTax;
    }

	public function setValidUntilDate($validUntilDate) {
        $this->validUntilDate = $validUntilDate;
    }

	public function getQuotationId(){
		return $this->quotationId;
	}

	public function setQuotationId($quotationId){
		$this->quotationId = $quotationId;
	}

	public function getDetailNo(){
		return $this->detailNo;
	}

	public function setDetailNo($detailNo){
		$this->detailNo = $detailNo;
	}

	public function getProductId(){
		return $this->productId;
	}

	public function setProductId($productId){
		$this->productId = $productId;
	}

	public function getQuantity(){
		return $this->quantity;
	}

	public function setQuantity($quantity){
		$this->quantity = $quantity;
	}

	public function getComment(){
		return $this->comment;
	}

	public function setComment($comment){
		$this->comment = $comment;
	}
	
}