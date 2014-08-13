<?php
/**
 *
 * @author Hieunm
 *
 */
class Core_Service_ContactService extends Core_Service_Abstract {
	/**
	 * getAskContactByUserId
	 * @param $userId, $askAnswerFlg, $order, $start, $end
	 * @return Array list
	 */
	public function getAskContactByUserId($userId, $paginatorData) {
		try {
			$db = new Core_Db_ContactInfoDb();
			return $db->getAskContactByUserId($userId, $paginatorData);
		} catch (Exception $e) {
			parent::writeLog(__CLASS__, __METHOD__, $e);
			return FALSE;
		}
	}
	
	/**
	 * getAskContactByUserName
	 * @param unknown $userName
	 * @param unknown $paginatorData
	 * @return Ambigous <$res, multitype:, multitype:unknown >|boolean
	 */
	public function getAskContactByUserName($userName, $paginatorData) {
		try {
			$db = new Core_Db_ContactInfoDb();
			return $db->getAskContactByUserName($userName, $paginatorData);
		} catch (Exception $e) {
			parent::writeLog(__CLASS__, __METHOD__, $e);
			return FALSE;
		}
	}

	public function countAskContactByUserName($userName=null){
		try {
			$mailtempDb=new Core_Db_ContactInfoDb();
			$resulcount=$mailtempDb->countAskContactByUserName($userName);
			return Core_Util_Helper::getDataRow($resulcount, "count", 0);
		}catch (Exception $e){
			parent::writeLog(__CLASS__, __METHOD__, $e);
			return false;
		}
	}
	
	
	/**
	 * getAnswerContactByContactId
	 * @param $contactId, $askAnswerFlg, $order, $start, $end
	 * @return Array list
	 */
	public function getAnswerContactByContactId($contactId, $paginatorData) {
		try {
			$db = new Core_Db_ContactInfoDb();
			return $db->getAnswerContactByContactId($contactId, $paginatorData);
		} catch (Exception $e) {
			parent::writeLog(__CLASS__, __METHOD__, $e);
			return FALSE;
		}
	}

	/**
	 * insertAskContact
	 * @param Core_Models_ContactInfo $contactInfo
	 * @return Boolean
	 */
	public function insertAskContact(Core_Models_ContactInfo $contactInfo) {
		try {
			$db = new Core_Db_ContactInfoDb();
			$this->beginTransaction();
			$db->insertAskContact($contactInfo);
			$this->commit();
			return TRUE;
		} catch (Exception $e) {
			parent::writeLog(__CLASS__, __METHOD__, $e);
			$this->rollBack();
			return FALSE;
		}
	}
	
	/**
	 * insertAskContact
	 * @param Core_Models_ContactInfo $contactInfo
	 * @return Boolean
	 */
	public function insertNewReplyContact(Core_Models_ContactInfo $contactInfo) {
		try {
			$db = new Core_Db_ContactInfoDb();
			$this->beginTransaction();
			$nextSeq = $db->getNextSeqOfContact($contactInfo->getContactId());
			$contactInfo->setSeq($nextSeq);
			$db->insertAskContact($contactInfo);
			$this->commit();
			return TRUE;
		} catch (Exception $e) {
			parent::writeLog(__CLASS__, __METHOD__, $e);
			$this->rollBack();
			return FALSE;
		}
	}
}