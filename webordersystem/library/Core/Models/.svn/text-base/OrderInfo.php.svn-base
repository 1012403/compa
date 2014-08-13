<?php
class Core_Models_OrderInfo extends Core_Models_MasterModel {
	public static $TOTAL_FIELD = 23;
	private  $orderId ;
	private  $userId ;
	private  $orderDateTime ;
	private  $shipppingHopeDate ;
	private  $orderStatus ;
	private  $usedPoint ;
	private  $bonusPoint ;
	// ADD 20140513 Hieunm start
	private $shippingDesName;
	private $postNo;
	private $address1;
	private $address2;
	private $telNo;
	private $faxNo;
	private $transType;
	private $remark;
	// ADD 20140513 Hieunm end
	// extra properties
	private $username;
	private $deliveryName;

	// Attribute of Core_Models_OrderDetailInfo
	private  $detailNo;
	private  $productId ;
	private  $quantity ;
	private  $price;
	private  $priceIncludingTax;
	private  $tax;
	private  $shippingFee;
	private  $totalPrice;

	// Attribute of Core_Models_MstProduct
	private $productName;
  	private $productNo;

  	private $loginName;

  	private $orderStatusName;
  	private $transTypeName;

	public function __construct($data = null) {
		parent::__construct($data);
		if ($data != null) {
			if ($data instanceof Zend_Db_Table_Row_Abstract
			|| is_array($data) == TRUE) {
				$this->orderId			= $this->getData($data, 'order_id');
				$this->userId			= $this->getData($data, 'user_id');
				$this->orderDateTime	= $this->getData($data, 'order_date_time');
				$this->shipppingHopeDate= $this->getData($data, 'shippping_hope_date');
				$this->orderStatus		= $this->getData($data, 'order_status');
				$this->usedPoint		= $this->getData($data, 'used_point');
				$this->bonusPoint		= $this->getData($data, 'bonus_point');
				// ADD 20140513 Hieunm start
				$this->shippingDesName	= $this->getData($data, 'shipping_des_name');
				$this->postNo			= $this->getData($data, 'post_no');
				$this->address1			= $this->getData($data, 'address1');
				$this->address2			= $this->getData($data, 'address2');
				$this->telNo			= $this->getData($data, 'tel_no');
				$this->faxNo			= $this->getData($data, 'fax_no');
				$this->transType		= $this->getData($data, 'trans_type');
				$this->remark			= $this->getData($data, 'remark');
				// ADD 20140513 Hieunm end

				$this->username			= $this->getData($data, 'user_name');
				$this->deliveryName			= $this->getData($data, 'shipping_des_name');

				$this->loginName			= $this->getData($data, 'login_username');
				$this->detailNo				= $this->getData($data, 'detail_no');
				$this->productName			= $this->getData($data, 'product_name');
				$this->productNo			= $this->getData($data, 'product_no');
				$this->price				= $this->getData($data, 'price');
				$this->priceIncludingTax	= $this->getData($data, 'price_including_tax');
				$this->tax					= $this->getData($data, 'tax');
				$this->quantity				= $this->getData($data, 'quantity');
				$this->totalPrice			= $this->getData($data, 'total_price');
				$this->shippingFee			= $this->getData($data, 'shipping_fee');

				$this->productId			= $this->getData($data, 'product_id');

				$this->orderStatusName		= $this->getData($data, 'order_status_name');
				$this->transTypeName		= $this->getData($data, 'trans_type_name');
			}
		}
	}

	public function toArray() {
		$arr = parent::toArray();
		$arr["order_id"] 		= $this->orderId;
		$arr["user_id"] 		= $this->userId;
		$arr["order_date_time"] = $this->orderDateTime;
		$arr["shippping_hope_date"] = $this->shipppingHopeDate;
		$arr["order_status"] 	= $this->orderStatus;
		$arr["used_point"] 		= $this->usedPoint;
		$arr["bonus_point"] 	= $this->bonusPoint;
		// ADD 20140513 Hieunm start
		$arr["shipping_des_name"]	= $this->shippingDesName;
		$arr["post_no"]				= $this->postNo;
		$arr["address1"]			= $this->address1;
		$arr["address2"]			= $this->address2;
		$arr["tel_no"]				= $this->telNo;
		$arr["fax_no"]				= $this->faxNo;
		$arr["trans_type"]			= $this->transType;
		$arr["remark"]				= $this->remark;
		// ADD 20140513 Hieunm end
		return $arr;
	}

	/**
	 * getHeaderCsv
	 * @return multitype:string
	 */
	public static function getHeaderCsv() {
		$arrHeader = array();
		$arrHeader[] = "注文No"; 			//order_id
		$arrHeader[] = "ログインユーザＩＤ"; 		//login_username
		$arrHeader[] = "注文日時"; 			//order_date_time
		$arrHeader[] = "発送日時指定"; 		//shipping_hope_date
		$arrHeader[] = "注文状態"; 			//order_status
		$arrHeader[] = "ポイント利用"; 		//used_point
		$arrHeader[] = "発送先名"; 			//shipping_des_name
		$arrHeader[] = "郵便番号"; 			//post_no
		$arrHeader[] = "住所１";				//address1
		$arrHeader[] = "住所2";				//address2
		$arrHeader[] = "電話番号";			//tel_no
		$arrHeader[] = "ＦＡＸ番号";			//fax_no
		$arrHeader[] = "発送方法";			//trans_type
		$arrHeader[] = "備考";				//remark
		$arrHeader[] = "明細No.";			//detail_no
		$arrHeader[] = "商品名";				//product_name
		$arrHeader[] = "商品番号";			//product_no
		$arrHeader[] = "注文価格（税抜き）";	//price
		$arrHeader[] = "注文明細情報";		//price_including_tax
		$arrHeader[] = "消費税";				//tax
		$arrHeader[] = "数量";				//quantity
		$arrHeader[] = "注文金額";			//total_price
		$arrHeader[] = "送料";				//shipping_fee

		return $arrHeader;
	}

	/**
	 * toCsvData
	 * @return array $arrData
	 */
	public function toCsvData() {
		$arrData = array();
		$arrData[] = $this->getOrderId();
		$arrData[] = $this->getLoginName();
		$arrData[] = str_replace("-", "/", $this->getOrderDateTime());		//$this->getOrderDateTime();
		$arrData[] = str_replace("-", "/", $this->getShipppingHopeDate());	//$this->getShipppingHopeDate();
		$arrData[] = ($this->getOrderStatus() !== '' && $this->getOrderStatus() !== null) ? $this->getOrderStatus() . ":" . $this->getOrderStatusName() : '';
		$arrData[] = $this->getUsedPoint();
		$arrData[] = $this->getShippingDesName();
		$arrData[] = $this->getPostNo();
		$arrData[] = $this->getAddress1();
		$arrData[] = $this->getAddress2();
		$arrData[] = $this->getTelNo();
		$arrData[] = $this->getFaxNo();
		$arrData[] = ($this->getTransType() !== '' && $this->getTransType() !== null) ? $this->getTransType() . ":" . $this->getTransTypeName() : '';
		$arrData[] = $this->getRemark();

		$arrData[] = $this->getDetailNo();

		$arrData[] = $this->getProductName();
		$arrData[] = $this->getProductNo();

		$arrData[] = $this->getPrice();
		$arrData[] = $this->getPriceIncludingTax();
		$arrData[] = $this->getTax();
		$arrData[] = $this->getQuantity();
		$arrData[] = $this->getTotalPrice();
		$arrData[] = $this->getShippingFee();

		return $arrData;
	}

	/**
	 * createOrderInfoFromCsvRow
	 * @return Core_Models_OrderInfo $mstCategory
	 */
	public function createOrderInfoFromCsvRow($row){
		$csvAgent = new Core_Models_CsvAgent();
		$arr = $csvAgent->convertCsvRowToArray($row);
		if (count($arr) == self::$TOTAL_FIELD) {
			$orderInfo = new Core_Models_OrderInfo();
			$orderInfo->setOrderId($arr[0]);

			$orderInfo->setLoginName($arr[1]);

			$orderDateTimeConv = new DateTime($arr[2]);
			$orderDateTime = $orderDateTimeConv->format('Y-m-d H:i:s');
			$orderInfo->setOrderDateTime($orderDateTime);

			$shipppingHopeDateConv = new DateTime($arr[3]);
			$shipppingHopeDate = $shipppingHopeDateConv->format('Y-m-d ');
			$orderInfo->setShipppingHopeDate($shipppingHopeDate);

			// Get orderStatus Code
			$orderStatus = explode(":", $arr[4]);
			$orderStatus = $orderStatus[0];
			$orderInfo->setOrderStatus($orderStatus);
			$orderInfo->setUsedPoint($arr[5]);
			$orderInfo->setShippingDesName($arr[6]);
			$orderInfo->setPostNo($arr[7]);
			$orderInfo->setAddress1($arr[8]);
			$orderInfo->setAddress2($arr[9]);
			$orderInfo->setTelNo($arr[10]);
			$orderInfo->setFaxNo($arr[11]);
			// Get transType Code
			$transType = explode(":", $arr[12]);
			$transType = $transType[0];
			$orderInfo->setTransType($transType);
			$orderInfo->setRemark($arr[13]);

			$orderInfo->setDetailNo($arr[14]);

			$orderInfo->setProductName($arr[15]);
			$orderInfo->setProductNo($arr[16]);

			$orderInfo->setPrice($arr[17]);
			$orderInfo->setPriceIncludingTax($arr[18]);
			$orderInfo->setTax($arr[19]);
			$orderInfo->setQuantity($arr[20]);
			$orderInfo->setTotalPrice($arr[21]);
			$orderInfo->setShippingFee($arr[22]);

			return $orderInfo;
		} else {
			throw new Exception("Csv field not match to OrderInfo Obj");
		}
	}

	public function getOrderId(){
		return $this->orderId;
	}

	public function setOrderId($orderId){
		$this->orderId = $orderId;
	}

	public function getUserId(){
		return $this->userId;
	}

	public function setUserId($userId){
		$this->userId = $userId;
	}

	public function getOrderDateTime(){
		return $this->orderDateTime;
	}

	public function setOrderDateTime($orderDateTime){
		$this->orderDateTime = $orderDateTime;
	}

	public function getShipppingHopeDate(){
		return $this->shipppingHopeDate;
	}

	public function setShipppingHopeDate($shipppingHopeDate){
		$this->shipppingHopeDate = $shipppingHopeDate;
	}

	public function getOrderStatus(){
		return $this->orderStatus;
	}

	public function setOrderStatus($orderStatus){
		$this->orderStatus = $orderStatus;
	}

	public function getUsedPoint(){
		return $this->usedPoint;
	}

	public function setUsedPoint($usedPoint){
		$this->usedPoint = $usedPoint;
	}

	public function getBonusPoint(){
		return $this->bonusPoint;
	}

	public function setBonusPoint($bonusPoint){
		$this->bonusPoint = $bonusPoint;
	}

	// ADD 20140513 Hieunm start
	public function getShippingDesName(){
		return $this->shippingDesName;
	}

	public function setShippingDesName($shippingDesName){
		$this->shippingDesName = $shippingDesName;
	}

	public function getPostNo(){
		return $this->postNo;
	}

	public function setPostNo($postNo){
		$this->postNo = $postNo;
	}

	public function getAddress1(){
		return $this->address1;
	}

	public function setAddress1($address1){
		$this->address1 = $address1;
	}

	public function getAddress2(){
		return $this->address2;
	}

	public function setAddress2($address2){
		$this->address2 = $address2;
	}

	public function getTelNo(){
		return $this->telNo;
	}

	public function setTelNo($telNo){
		$this->telNo = $telNo;
	}

	public function getFaxNo(){
		return $this->faxNo;
	}

	public function setFaxNo($faxNo){
		$this->faxNo = $faxNo;
	}

	public function getTransType(){
		return $this->transType;
	}

	public function setTransType($transType){
		$this->transType = $transType;
	}

	public function getRemark(){
		return $this->remark;
	}

	public function setRemark($remark){
		$this->remark = $remark;
	}
	// ADD 20140513 Hieunm end

	public function getOrderStatusAsString() {
		$db = new Core_Db_MstClassDb();
		$arrStatus = $db->getMstClassByItemType(Core_Util_Const::ORDER_STATUS);

		$arr = array();
		/* @var $mstClass Core_Models_MstClass */
		foreach ($arrStatus as $key => $mstClass) {
			$arr[$mstClass->getItemCd()] = "<span style=\"color:" . $mstClass->getNote1() . ";\">" . $mstClass->getItemName() . "</span>";
		}

		if (isset($arr[$this->orderStatus])) {
			return $arr[$this->orderStatus];
		} else {
			return "";
		}

	}

	public function getUsername(){
		return $this->username;
	}

	public function setUsername($username){
		$this->username = $username;
	}

	public function getDeliveryName(){
		return $this->deliveryName;
	}

	public function setDeliveryName($deliveryName){
		$this->deliveryName = $deliveryName;
	}

	public function getFormatedOrderDate() {
		if (Core_Util_Helper::isNotEmpty($this->orderDateTime)) {
			$date = date_create($this->orderDateTime);
			return date_format($date, 'Y/m/d H:i:s');
		} else {
			return "";
		}

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

	public function getTotalPrice(){
		return $this->totalPrice;
	}

	public function setTotalPrice($totalPrice){
		$this->totalPrice = $totalPrice;
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

	public function getLoginName(){
		return $this->loginName;
	}

	public function setLoginName($loginName){
		$this->loginName = $loginName;
	}

	public function getOrderStatusName(){
		return $this->orderStatusName;
	}

	public function setOrderStatusName($orderStatusName){
		$this->orderStatusName = $orderStatusName;
	}

	public function getTransTypeName(){
		return $this->transTypeName;
	}

	public function setTransTypeName($transTypeName){
		$this->transTypeName = $transTypeName;
	}
}