<?php
class Core_Models_MstProductExtra extends Core_Models_MasterModel {
    private $productId;
    private $productDetailInfo;
    private $detailClass;
    private $displayOrder;
    private $detailClassLabel;
    private $itemOrder;

    public function __construct($data = null) {
        if ($data != null) {
          if ($data instanceof Zend_Db_Table_Row_Abstract
          || is_array($data) == TRUE) {
            $this->productId	= $this->getData($data, 'product_id');
            $this->productDetailInfo = $this->getData($data, 'product_detail_info');
            $this->detailClass	= $this->getData($data, 'detail_class');
            $this->displayOrder = $this->getData($data, 'display_order');
            $this->itemOrder = $this->getData($data, 'item_order');
          }
        }
    }

    public function toArray() {
        $arr = parent::toArray();
        $arr["product_id"] 	= $this->productId;
        $arr["product_detail_info"] = $this->productDetailInfo;

        return $arr;
    }
	public function getProductId() {
        return $this->productId;
    }

	public function getProductDetailInfo() {
        return $this->productDetailInfo;
    }

	public function setProductId($productId) {
        $this->productId = $productId;
    }

	public function setProductDetailInfo($productDetailInfo) {
        $this->productDetailInfo = $productDetailInfo;
    }
    
    public function getDetailClass(){
    	return $this->detailClass;
    }
    
    public function setDetailClass($detailClass){
    	$this->detailClass = $detailClass;
    }
    
    public function getDisplayOrder(){
    	return $this->displayOrder;
    }
    
    public function setDisplayOrder($displayOrder){
    	$this->displayOrder = $displayOrder;
    }
    
    public function getDetailClassLabel(){
    	return $this->detailClassLabel;
    }
    
    public function setDetailClassLabel($detailClassLabel){
    	$this->detailClassLabel = $detailClassLabel;
    }
    
    public function getItemOrder(){
    	return $this->itemOrder;
    }
    
    public function setItemOrder($itemOrder){
    	$this->itemOrder = $itemOrder;
    }

}