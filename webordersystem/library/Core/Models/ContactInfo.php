<?php
/**
 *
 * @author Hieunm
 *
 */
class Core_Models_ContactInfo extends Core_Models_MasterModel {
	private $contactId;
	private $seq;
	private $userId;
	private $contactClass;
	private $contactDateTime;
	private $askAnswerFlg;
	private $title;
	private $content;

	private $userName;
	/**
	 *
	 * @param string $data
	 */
	public function __construct($data = null) {
		parent::__construct($data);
		if ($data != null) {
			if ($data instanceof Zend_Db_Table_Row_Abstract
			|| is_array($data) == TRUE) {
				$this->contactId         = $this->getData($data, 'contact_id');
				$this->seq               = $this->getData($data, 'seq');
				$this->userId            = $this->getData($data, 'user_id' );
				$this->contactClass      = $this->getData($data, 'contact_class');
				$this->contactDateTime   = $this->getData($data, 'contact_date_time');
				$this->askAnswerFlg      = $this->getData($data, 'ask_answer_flg');
				$this->title             = $this->getData($data, 'title');
				$this->content           = $this->getData($data, 'content');
				
				$this->userName           = $this->getData($data, 'user_name');
			}
		}
	}

	/**
	 * (non-PHPdoc)
	 * @see Core_Models_Domain::toArray()
	 */
	public function toArray() {
		$arr = parent::toArray();
		$arr["contact_id"]        = $this->contactId;
		$arr["seq"]               = $this->seq;
		$arr["user_id"]           = $this->userId;
		$arr["contact_class"]     = $this->contactClass;
		$arr["contact_date_time"] = $this->contactDateTime;
		$arr["ask_answer_flg"]    = $this->askAnswerFlg;
		$arr["title"]             = $this->title;
		$arr["content"]           = $this->content;
		return $arr;
	}

	public function getContactId(){
		return $this->contactId;
	}

	public function setContactId($contactId){
		$this->contactId = $contactId;
	}

	public function getSeq(){
		return $this->seq;
	}

	public function setSeq($seq){
		$this->seq = $seq;
	}

	public function setUserId($userId) {
		$this->userId = $userId;
	}

	public function getUserId() {
		return $this->userId;
	}

	public function getContactClass(){
		return $this->contactClass;
	}

	public function setContactClass($contactClass){
		$this->contactClass = $contactClass;
	}

	public function getContactDateTime(){
		return $this->contactDateTime;
	}

	public function setContactDateTime($contactDateTime){
		$this->contactDateTime = $contactDateTime;
	}

	public function getAskAnswerFlg(){
		return $this->askAnswerFlg;
	}

	public function setAskAnswerFlg($askAnswerFlg){
		$this->askAnswerFlg = $askAnswerFlg;
	}

	public function getTitle(){
		return $this->title;
	}

	public function setTitle($title){
		$this->title = $title;
	}

	public function getContent(){
		return $this->content;
	}

	public function setContent($content){
		$this->content = $content;
	}
	
	public function getUserName(){
		return $this->userName;
	}
	
	public function setUserName($userName){
		$this->userName = $userName;
	}
}