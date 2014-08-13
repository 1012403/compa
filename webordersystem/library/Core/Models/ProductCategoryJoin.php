<?php
class Core_Models_ProductCategoryJoin extends Core_Models_MasterModel {
    private $productId;
    private $categoryId;

    public function __construct($data = null) {
        if ($data != null) {
          if ($data instanceof Zend_Db_Table_Row_Abstract
          || is_array($data) == TRUE) {
            $this->productId  = $this->getData($data, 'product_id');
            $this->categoryId = $this->getData($data, 'category_id');
          }
        }
    }

    public function toArray() {
        $arr = parent::toArray();
        $arr["product_id"]  = $this->productId;
        $arr["category_id"] = $this->categoryId;

        return $arr;
    }
  public function getProductId() {
        return $this->productId;
    }

  public function getCategoryId() {
        return $this->categoryId;
    }

  public function setProductId($productId) {
        $this->productId = $productId;
    }

  public function setCategoryId($categoryId) {
        $this->categoryId = $categoryId;
    }
}