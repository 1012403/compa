<?php
class Core_Models_MstCategory extends Core_Models_MasterModel {
	public static $TOTAL_FIELD = 4;
	public static $TOTAL_FIELD_IMPORT = 2;
    private $categoryId;
    private $categoryName;
    private $parentId;
    private $displayOrder;
    private $displayFlg;
    
    // extra properties
    private $parentName;

    public function __construct($data = null) {
    	parent::__construct($data);
        if ($data != null) {
            if ($data instanceof Zend_Db_Table_Row_Abstract
                || is_array($data) == TRUE) {
                $this->categoryId	= $this->getData($data, 'category_id');
                $this->categoryName	= $this->getData($data, 'category_name');
                $this->parentId	= $this->getData($data, 'parent_id');
                $this->displayOrder	= $this->getData($data, 'display_order');
                $this->displayFlg	= $this->getData($data, 'display_flg');
            }
        }
    }

    public function toArray() {
        $arr = parent::toArray();
        $arr["category_id"] 	= $this->categoryId;
        $arr["category_name"] 	= $this->categoryName;
        $arr["parent_id"] 		= $this->parentId;
        $arr["display_order"]	= $this->displayOrder;
        $arr["display_flg"] 	= $this->displayFlg;

        return $arr;
    }
    
    public static function getHeaderCsv() {
    	$arrHeader = array();
    	$arrHeader[] = "親カテゴリー";//Category Name Parent
    	$arrHeader[] = "子カテゴリー";//Category Name
    	
    	return $arrHeader;
    }
    

    public function toCsvData() {
    	$arrData = array();
    	$arrData[] = $this->getParentName();
    	$arrData[] = $this->getCategoryName();
    	return $arrData;
    }
    
    public static function createMstCategoryFromCsvRow($row) {
    	$csvAgent = new Core_Models_CsvAgent();
    	$arr = $csvAgent->convertCsvRowToArray($row);
    	if (count($arr) == self::$TOTAL_FIELD_IMPORT) {
    		$mstCategory = new Core_Models_MstCategory();
    		$mstCategory->setParentName($arr[0]);
    		$mstCategory->setCategoryName($arr[1]);
    		
    		return $mstCategory;
    	} else {
    		throw new Exception("Csv field not match to MstCategory Obj");
    	}
    }
    
    public function getCategoryId() {
        return $this->categoryId;
    }

    public function getCategoryName() {
        return $this->categoryName;
    }

    public function getParentId() {
        return $this->parentId;
    }

    public function getDisplayOrder() {
        return $this->displayOrder;
    }

    public function getDisplayFlg() {
        return $this->displayFlg;
    }

    public function setCategoryId($categoryId) {
        $this->categoryId = $categoryId;
    }

    public function setCategoryName($categoryName) {
        $this->categoryName = $categoryName;
    }

    public function setParentId($parentId) {
        $this->parentId = $parentId;
    }

    public function setDisplayOrder($displayOrder) {
        $this->displayOrder = $displayOrder;
    }

    public function setDisplayFlg($displayFlg) {
        $this->displayFlg = $displayFlg;
    }
    
    public function getParentName(){
    	return $this->parentName;
    }
    
    public function setParentName($parentName){
    	$this->parentName = $parentName;
    }
}