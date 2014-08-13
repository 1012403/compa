<?php
class Core_Models_MstMagnification extends Core_Models_Domain {
    private $applyStartDate;
    private $productId;
    private $magnificationPoint;

    public function __construct($data = null) {
        if ($data != null) {
          if ($data instanceof Zend_Db_Table_Row_Abstract
          || is_array($data) == TRUE) {
            $this->applyStartDate	= $this->getData($data, 'apply_start_date');
            $this->productId	= $this->getData($data, 'product_id');
            $this->magnificationPoint = $this->getData($data, 'magnification_point');
          }
        }
    }

    public function toArray() {
        $arr = parent::toArray();
        $arr["apply_start_date"] = $this->applyStartDate;
        $arr["product_id"] 	= $this->productId;
        $arr["magnification_point"] = $this->magnificationPoint;

        return $arr;
    }
	public function getApplyStartDate() {
        return $this->applyStartDate;
    }

	public function getProductId() {
        return $this->productId;
    }

	public function getMagnificationPoint() {
        return $this->magnificationPoint;
    }

	public function setApplyStartDate($applyStartDate) {
        $this->applyStartDate = $applyStartDate;
    }

	public function setProductId($productId) {
        $this->productId = $productId;
    }

	public function setMagnificationPoint($magnificationPoint) {
        $this->magnificationPoint = $magnificationPoint;
    }
}