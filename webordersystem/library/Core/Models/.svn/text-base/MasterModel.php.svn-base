<?php
/**
 * 
 * @author nhanlt
 *
 */
class Core_Models_MasterModel extends Core_Models_Domain {
	const INSERT_ID_FIELD = "insert_user_id";
	const INSERT_DATE_FIELD = "insert_ymd";
	const UPDATE_ID_FIELD = "update_user_id";
	const UPDATE_DATE_FIELD = "update_ymd";
	const DELETE_FIELD = "delete_flg";
	const DELETE_VAL = "1";
	protected $deleteFlg;
	protected $insertYmd;
	protected $updateYmd;
	protected $insertUserId;
	protected $updateUserId;
	public function __construct($data = null) {
		if ($data != null) {
			if ($data instanceof Zend_Db_Table_Row_Abstract || is_array ( $data ) == TRUE) {
				$this->deleteFlg = $this->getData ( $data, self::DELETE_FIELD );
				$this->insertUserId = $this->getData ( $data, self::INSERT_ID_FIELD );
				$this->insertYmd = $this->getData ( $data, self::INSERT_DATE_FIELD );
				$this->updateUserId = $this->getData ( $data, self::UPDATE_ID_FIELD );
				$this->updateYmd = $this->getData ( $data, self::UPDATE_DATE_FIELD );
			}
		} else {
			$this->deleteFlg = '0';
		}
	}
	
	/**
	 * (non-PHPdoc)
	 * 
	 * @see Core_Models_Domain::toArray()
	 */
	public function toArray() {
		$arr = parent::toArray ();
		$arr [self::DELETE_FIELD] = $this->deleteFlg;
		$arr [self::INSERT_ID_FIELD] = $this->insertUserId;
		$arr [self::INSERT_DATE_FIELD] = $this->insertYmd;
		$arr [self::UPDATE_ID_FIELD] = $this->updateUserId;
		$arr [self::UPDATE_DATE_FIELD] = $this->updateYmd;
		return $arr;
	}
	public function getDeleteFlg() {
		return $this->deleteFlg;
	}
	public function setDeleteFlg($deleteFlg) {
		$this->deleteFlg = $deleteFlg;
	}
	public function getInsertYmd() {
		return $this->insertYmd;
	}
	public function setInsertYmd($insertYmd) {
		$this->insertYmd = $insertYmd;
	}
	public function getUpdateYmd() {
		return $this->updateYmd;
	}
	public function setUpdateYmd($updateYmd) {
		$this->updateYmd = $updateYmd;
	}
	public function getInsertUserId() {
		return $this->insertUserId;
	}
	public function setInsertUserId($insertUserId) {
		$this->insertUserId = $insertUserId;
	}
	public function getUpdateUserId() {
		return $this->updateUserId;
	}
	public function setUpdateUserId($updateUserId) {
		$this->updateUserId = $updateUserId;
	}
}