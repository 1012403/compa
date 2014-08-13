<?php
/**
 * 
 * @author nhanlt
 *
 */
class Core_Models_OperationLogInfo extends Core_Models_Domain {
	private $logId;
	private $userId;
	private $logKind;
	private $operateContent;
	private $operateDetail;
	private $logDateTime;
	public function __construct($data = null) {
		parent::__construct ( $data );
		if ($data != null) {
			if ($data instanceof Zend_Db_Table_Row_Abstract || is_array ( $data ) == TRUE) {
				$this->logId			= $this->getData ( $data, 'log_id' );
				$this->userId			= $this->getData ( $data, 'user_id' );
				$this->logKind			= $this->getData ( $data, 'log_kind' );
				$this->operateContent	= $this->getData ( $data, 'operate_content' );
				$this->operateDetail	= $this->getData ( $data, 'operate_detail' );
				$this->logDateTime	= $this->getData ( $data, 'log_date_time' );
			}
		}
	}
	
	/**
	 * (non-PHPdoc)
	 * @see Core_Models_Domain::toArray()
	 */
	public function toArray() {
		$arr = parent::toArray ();
		$arr ["log_id"]				= $this->logId;
		$arr ["user_id"]			= $this->userId;
		$arr ["log_kind"]			= $this->logKind;
		$arr ["operate_content"]	= $this->operateContent;
		$arr ["operate_detail"]		= $this->operateDetail;
		return $arr;
	}
	
	/**
	 * 
	 * @return Ambigous <unknown, string, unknown>
	 */
	public function getLogId() {
		return $this->logId;
	}
	
	/**
	 * 
	 * @param unknown $logId
	 */
	public function setLogId($logId) {
		$this->logId = $logId;
	}
	
	/**
	 * 
	 * @return Ambigous <unknown, string, unknown>
	 */
	public function getUserId() {
		return $this->userId;
	}
	
	/**
	 * 
	 * @param unknown $userId
	 */
	public function setUserId($userId) {
		$this->userId = $userId;
	}
	
	/**
	 * 
	 * @return Ambigous <unknown, string, unknown>
	 */
	public function getLogKind() {
		return $this->logKind;
	}
	
	/**
	 * 
	 * @param unknown $logKind
	 */
	public function setLogKind($logKind) {
		$this->logKind = $logKind;
	}
	
	/**
	 * getOperateContent
	 * @return Ambigous <string, string, unknown>
	 */
	public function getOperateContent() {
		return $this->operateContent;
	}
	
	/**
	 * setOperateContent
	 * @param string $operateContent
	 */
	public function setOperateContent($operateContent) {
		$this->operateContent = $operateContent;
	}
	
	/**
	 * 
	 * @return Ambigous <unknown, string>
	 */
	public function getOperateDetail(){
		return $this->operateDetail;
	}
	
	/**
	 * 
	 * @param unknown $operateDetail
	 */
	public function setOperateDetail($operateDetail){
		$this->operateDetail = $operateDetail;
	}
	
	/**
	 * 
	 * @return Ambigous <unknown, string>
	 */
	public function getLogDateTime(){
		return $this->logDateTime;
	}
	
	/**
	 * 
	 * @param unknown $logDateTime
	 */
	public function setLogDateTime($logDateTime){
		$this->logDateTime = $logDateTime;
	}
}