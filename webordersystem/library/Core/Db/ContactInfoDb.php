<?php
/**
 *
 * @author Hieunm
 *
 */
class Core_Db_ContactInfoDb extends Core_Db_Persistent {
	protected $_name          = Core_Util_TableNames::CONTACT_INFO;
	protected $_primary       = 'contact_id';
	protected $_instanceClass = 'Core_Models_ContactInfo';

	/**
	 * getAskContactByUserId
	 * @param $userId, $askAnswerFlg, $order, $start, $end
	 * @return $res
	 */
	public function getAskContactByUserId($userId, $paginatorData) {
		$query = $this->select()->from(array("contact" =>$this->_name));
		$query->setIntegrityCheck(false);

		$query->where('contact.delete_flg = 0 ');
		$query->where('contact.user_id = ? ', $userId);
		$query->where('contact.seq = ? ', Core_Util_Const::CONTACT_SEQ_FIRST);
		$query->order('contact_date_time DESC');

		//page
		if($paginatorData != null){
			if ($paginatorData['itemCountPerPage'] > 0) {
				$page     = $paginatorData['currentPage'];
				$rowCount = $paginatorData['itemCountPerPage'];
				$query->limitPage($page, $rowCount);

			}
		}

		$rows = $this->fetchAll($query);
		$arrContact	= $this->setRowsToArray($rows);
		return $arrContact;
	}

	/**
	 * getAskContactByUserName
	 * @param $username
	 * @return $res
	 */
	public function getAskContactByUserName($username, $paginatorData) {
		$query = $this->select()->from(array("contact" =>$this->_name));
		$query->joinInner(array('mstUser' => Core_Util_TableNames::MST_USER),
				'mstUser.user_id = contact.user_id', array('mstUser.user_name'));
		$query->setIntegrityCheck(false);

		$query->where('contact.delete_flg = 0 ');
		if($username != ""){
			$query->where('mstUser.user_name like "%?%" ', new Zend_Db_Expr($username));
		}
		// ADD 20140425 Hieunm start
		$query->where('mstUser.delete_flg = ?', Core_Util_Const::DELETE_FLG_0);
		// ADD 20140425 Hieunm end
		$query->where('contact.seq = ? ', Core_Util_Const::CONTACT_SEQ_FIRST);
		$query->order('contact_date_time DESC');

		//page
		if($paginatorData != null){
			if ($paginatorData['itemCountPerPage'] > 0) {
				$page     = $paginatorData['currentPage'];
				$rowCount = $paginatorData['itemCountPerPage'];
				$query->limitPage($page, $rowCount);
			}
		}

		$rows = $this->fetchAll($query);
		//print $query;
		$arrContact	= $this->setRowsToArray($rows);
		return $arrContact;
	}
	/**
	 * count contact by username
	 * @param unknown $username
	 * @return Ambigous <multitype:, multitype:unknown >
	 */
	public function countAskContactByUserName($username) {
		$query = $this->select()->from(array("contact" =>$this->_name),array("count"=> "count(*)") );
		$query->setIntegrityCheck(false);
		$query->joinInner(array('mstUser' => Core_Util_TableNames::MST_USER),
				'mstUser.user_id = contact.user_id', null);

		$query->where('contact.delete_flg = 0 ');
		if($username != ""){
			$query->where('mstUser.user_name like "%?%" ', new Zend_Db_Expr($username));
		}
		// ADD 20140425 Hieunm start
		$query->where('mstUser.delete_flg = ?', Core_Util_Const::DELETE_FLG_0);
		// ADD 20140425 Hieunm end
		$query->where('contact.seq = ? ', Core_Util_Const::CONTACT_SEQ_FIRST);

		$rows = $this->fetchRow($query)->toArray();
		return $rows;
	}


	/**
	 * getAnswerContactByContactId
	 * @param $contactId, $askAnswerFlg, $order, $start, $end
	 * @return $res
	 */
	public function getAnswerContactByContactId($contactId, $paginatorData) {

		$query = $this->select()->from(array("contact" =>$this->_name));
		$query->setIntegrityCheck(false);

		$query->where('contact.delete_flg = 0 ');
		$query->where('contact.contact_id = ? ', $contactId);
		$query->order('contact.seq ASC');

		//page
		if($paginatorData != null){
			if ($paginatorData['itemCountPerPage'] > 0) {
				$page     = $paginatorData['currentPage'];
				$rowCount = $paginatorData['itemCountPerPage'];
				$query->limitPage($page, $rowCount);

			}
		}

		$rows = $this->fetchAll($query);
		$arrContact	= $this->setRowsToArray($rows);
		return $arrContact;
	}

	/**
	 * insertAskContact
	 * @param Core_Models_ContactInfo $contactInfo
	 * @return $res
	 */
	 public function insertAskContact(Core_Models_ContactInfo $contactInfo) {
		$res = false;
		if ($contactInfo !== null && $contactInfo !== false) {
			$res = $this->insertRecord($contactInfo);
		}

		return $res;
	}


	/**
	 * getNextSeqOfContact
	 * @param unknown $contactId
	 * @return Ambigous <boolean, id, mixed, multitype:>
	 */
	public function getNextSeqOfContact($contactId) {
		$query = $this->select()->from(array("contact" =>$this->_name), array('max(contact.seq) as maxValue'));
		$query->setIntegrityCheck(false);
		$query->where('contact.contact_id = ? ', $contactId);

		$rows = $this->fetchRow($query);

		return ($rows->maxValue + 1);
	}
}