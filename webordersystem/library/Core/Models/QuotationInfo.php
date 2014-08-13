<?php
class Core_Models_QuotationInfo extends Core_Models_MasterModel {
	private  $quotationId ;
	private  $userId ;
	private  $quotationDateTime ;
	private  $remark ;
	private  $status;
	
	public function __construct($data = null) {
		parent::__construct($data);
		if ($data != null) {
			if ($data instanceof Zend_Db_Table_Row_Abstract
			|| is_array($data) == TRUE) {
				$this->quotationId			= $this->getData($data, 'quotation_id');
				$this->userId				= $this->getData($data, 'user_id');
				$this->quotationDateTime	= $this->getData($data, 'quotation_date_time');
				$this->remark				= $this->getData($data, 'remark');
				$this->status				= $this->getData($data, 'status');
			}
		}
	}
	
	public function toArray() {
		$arr = parent::toArray();
		$arr["quotation_id"] 		= $this->quotationId;
		$arr["user_id"] 			= $this->userId;
		$arr["quotation_date_time"] = $this->quotationDateTime;
		$arr["remark"] 				= $this->remark;
		$arr["status"] 				= $this->status;
		return $arr;
	}
	
	
	public function getQuotationId(){
		return $this->quotationId;
	}

	public function setQuotationId($quotationId){
		$this->quotationId = $quotationId;
	}

	public function getUserId(){
		return $this->userId;
	}

	public function setUserId($userId){
		$this->userId = $userId;
	}

	public function getOrderDateTime(){
		return $this->quotationDateTime;
	}

	public function setQuotationDateTime($quotationDateTime){
		$this->quotationDateTime = $quotationDateTime;
	}

	public function getRemark(){
		return $this->remark;
	}

	public function setRemark($remark){
		$this->remark = $remark;
	}
	
	public function getStatus(){
		return $this->status;
	}
	
	public function setStatus($status){
		$this->status = $status;
	}
}