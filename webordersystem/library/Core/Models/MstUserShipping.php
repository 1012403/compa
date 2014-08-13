<?php
/**
 *
 * @author Nguyen
 *
 */
class Core_Models_MstUserShipping extends Core_Models_Domain {

	private $userId;
	private $shippingSeq;
	private $shippingDesName;
	private $postNo;
	private $address1;
	private $address2;
	private $telNo;
	private $faxNo;
	private $transType;
	private $remark;


	public function __construct($data = null) {
		if ($data != null) {
			if ($data instanceof Zend_Db_Table_Row_Abstract
			|| is_array($data) == TRUE) {
				$this->userId			= $this->getData($data, 'user_id');
				$this->shippingSeq		= $this->getData($data, 'shipping_seq');
				$this->shippingDesName	= $this->getData($data, 'shipping_des_name');
				$this->postNo			= $this->getData($data, 'post_no');
				$this->address1			= $this->getData($data, 'address1');
				$this->address2			= $this->getData($data, 'address2');
				$this->telNo			= $this->getData($data, 'tel_no');
				$this->faxNo			= $this->getData($data, 'fax_no');
				$this->transType		= $this->getData($data, 'trans_type');
				$this->remark			= $this->getData($data, 'remark');
			}
		}
	}

	/**
	 * (non-PHPdoc)
	 * @see Core_Models_Domain::toArray()
	 */
	public function toArray() {
		$arr = parent::toArray();
		$arr["user_id"]				= $this->userId;
		$arr["shipping_seq"]		= $this->shippingSeq;
		$arr["shipping_des_name"]	= $this->shippingDesName;
		$arr["post_no"]				= $this->postNo;
		$arr["address1"]			= $this->address1;
		$arr["address2"]			= $this->address2;
		$arr["tel_no"]				= $this->telNo;
		$arr["fax_no"]				= $this->faxNo;
		$arr["trans_type"]			= $this->transType;
		$arr["remark"]				= $this->remark;

		return $arr;
	}

	public function getUserId(){
		return $this->userId;
	}

	public function setUserId($userId){
		$this->userId = $userId;
	}

	public function getShippingSeq(){
		return $this->shippingSeq;
	}

	public function setShippingSeq($shippingSeq){
		$this->shippingSeq = $shippingSeq;
	}

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
}
