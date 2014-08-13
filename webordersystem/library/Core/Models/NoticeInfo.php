<?php
/**
 * 
 * @author Nhanlt
 *
 */
class Core_Models_NoticeInfo extends Core_Models_MasterModel {
	private $noticeId;
	private $userId;
	private $updateDate;
	private $content;
	
	public function __construct($data = null) {
		parent::__construct($data);
		if ($data != null) {
			if ($data instanceof Zend_Db_Table_Row_Abstract
			|| is_array($data) == TRUE) {
				$this->noticeId		= $this->getData($data, 'notice_id');
				$this->userId		= $this->getData($data, 'user_id');
				$this->updateDate	= $this->getData($data, 'update_date');
				$this->content		= $this->getData($data, 'content');
			}
		}
	}
	
	/**
	 * (non-PHPdoc)
	 * @see Core_Models_Domain::toArray()
	 */
	public function toArray() {
		$arr = parent::toArray();
		$arr["notice_id"]	= $this->noticeId;
		$arr["user_id"]		= $this->userId;
		$arr["update_date"]	= $this->updateDate;
		$arr["content"]		= $this->content;
		return $arr;
	}
	
	public function getUserId(){
		return $this->userId;
	}
	
	public function setUserId($userId){
		$this->userId = $userId;
	}
	
	public function getUpdateDate(){
		return $this->updateDate;
	}
	
	public function setUpdateDate($updateDate){
		$this->updateDate = $updateDate;
	}
	
	public function getContent(){
		return $this->content;
	}
	
	public function setContent($content){
		$this->content = $content;
	}

	public function getNoticeId(){
		return $this->noticeId;
	}
	
	public function setNoticeId($noticeId){
		$this->noticeId = $noticeId;
	}
	
}
