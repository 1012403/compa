<?php
class Core_Models_UserProductJoin extends Core_Models_Domain {
	public static $TOTAL_FIELD = 6;
	private  $productId ;
	private  $userId ;
	private  $validUntilDate ;
	private  $price;
	private  $priceIncludingTax ;
	private  $tax;
	
	//user
	private $userName;
	private $loginUsername;
	//product
	//private $productName;
	private $productNo;
	// extra properties
	
	private $productName;

	public function __construct($data = null) {
		parent::__construct($data);
		if ($data != null) {
			if ($data instanceof Zend_Db_Table_Row_Abstract
			|| is_array($data) == TRUE) {
				$this->productId = $this->getData($data, 'product_id');
				$this->userId = $this->getData($data, 'user_id');
				$this->validUntilDate = $this->getData($data, 'valid_until_date');
				$this->priceIncludingTax = $this->getData($data, 'price_including_tax');
				$this->price = $this->getData($data, 'price');
				$this->tax = $this->getData($data, 'tax');
				
				// extra properties
				$this->productName = $this->getData($data, 'product_name');
				
			}
		}
	}

	public function toArray() {
		$arr = parent::toArray();
		$arr["product_id"] = $this->productId;
		$arr["user_id"] = $this->userId;
		$arr["valid_until_date"] = $this->validUntilDate;
		$arr["price_including_tax"] = $this->priceIncludingTax;
		$arr["price"] = $this->price;
		$arr["tax"] = $this->tax;
		return $arr;
	}


	/**
	 * getHeaderCsv
	 * @return multitype:string
	 */
	public static function getHeaderCsv() {
		$arrHeader = array();
		$arrHeader[] = "ユーザ名"; //user_name
		$arrHeader[] = "ログインユーザＩＤ"; //login_username
		$arrHeader[] = "商品名"; //product_name
		$arrHeader[] = "商品番号"; //product_no
		$arrHeader[] = "有効期日"; //valid_until_date
		$arrHeader[] = "見積り単価(税抜)"; //price
		
		return $arrHeader;
	}
	
	public function toCsvData() {
		$arrData = array();
		$arrData[] = $this->getUserName();
		$arrData[] = $this->getLoginUsername();
		$arrData[] = $this->getProductName();
		$arrData[] = $this->getProductNo();
		$arrData[] = $this->getValidUntilDate();
		$arrData[] = $this->getPrice();
		return $arrData;
	}
	
	public function createUserProductJoinFromCsvRow($row){
		$csvAgent = new Core_Models_CsvAgent();
		$arr = $csvAgent->convertCsvRowToArray($row);
		if (count($arr) == self::$TOTAL_FIELD) {
			$mstUserProduc = new Core_Models_UserProductJoin();
			$mstUserProduc->setUserName($arr[0]);
			$mstUserProduc->setLoginUsername($arr[1]);
			$mstUserProduc->setProductName($arr[2]);
			$mstUserProduc->setProductNo($arr[3]);
			$mstUserProduc->setValidUntilDate($arr[4]);
			$mstUserProduc->setPrice($arr[5]);
	
			return $mstUserProduc;
		} else {
			throw new Exception("Csv field not match to UserProductJoin Obj");
		}
	}
	
	
	public function getProductId() {
        return $this->productId;
    }

	public function getUserId() {
        return $this->userId;
    }

	public function getValidUntilDate() {
        return $this->validUntilDate;
    }

	public function getPriceIncludingTax() {
        return $this->priceIncludingTax;
    }

	public function getTax() {
        return $this->tax;
    }

	public function setProductId($productId) {
        $this->productId = $productId;
    }

	public function setUserId($userId) {
        $this->userId = $userId;
    }

	public function setValidUntilDate($validUntilDate) {
        $this->validUntilDate = $validUntilDate;
    }

	public function setPriceIncludingTax($priceIncludingTax) {
        $this->priceIncludingTax = $priceIncludingTax;
    }

	public function setTax($tax) {
        $this->tax = $tax;
    }
    
    public function getPrice() {
    	return $this->price;
    }
    
    public function setPrice($price){
    	$this->price = $price;
    }
    
    public function getProductName(){
    	return $this->productName;
    }
    
    public function setProductName($productName){
    	$this->productName = $productName;
    }
    
    
    
    // product, user
	public function getUserName(){
		return $this->userName;
	}

	public function setUserName($userName){
		$this->userName = $userName;
	}

	public function getLoginUsername(){
		return $this->loginUsername;
	}

	public function setLoginUsername($loginUsername){
		$this->loginUsername = $loginUsername;
	}

	public function getProductNo(){
		return $this->productNo;
	}

	public function setProductNo($productNo){
		$this->productNo = $productNo;
	}
}