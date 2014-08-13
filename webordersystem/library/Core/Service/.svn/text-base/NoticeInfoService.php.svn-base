<?php
/**
 *
 * @author nhanlt
 *
 */
class Core_Service_NoticeInfoService extends Core_Service_Abstract {



	/**
	 *
	 * @var Core_Db_NoticeInfoDb
	 */
	private $noticeDb;

	function __construct() {
		parent::__construct();
		$this->noticeDb = new Core_Db_NoticeInfoDb();
	}


	/**
	 * getNoticeOfSale
	 * @param string $userId
	 * @return Core_Models_NoticeInfo
	 */
	public function getNews(){
		try {
			$db = $this->noticeDb;
			$news = $db->getNews();
			return $news;
		} catch (Exception $e) {
			parent::writeLog(__CLASS__, __METHOD__, $e);
			return null;
		}
	}

	/**
	 *
	 * @param string $userId
	 * @return Core_Models_NoticeInfo
	 */
	public function getNoticeOfSale($userId) {
		try {
			$db = $this->noticeDb;
			$notice = $db->getNoticeOfSale($userId);
			return $notice;
		} catch (Exception $e) {
			parent::writeLog(__CLASS__, __METHOD__, $e);
			return null;
		}
	}

	public function getListNoticeOfSaleUser($userId) {
		try {
			$db = $this->noticeDb;
			$arrNotice = $db->getListNoticeOfSaleUser($userId);
			return $arrNotice;
		} catch (Exception $e) {
			parent::writeLog(__CLASS__, __METHOD__, $e);
			return null;
		}
	}

	/**
	 * getNoticeById
	 * @param unknown $id
	 * @return Core_Models_NoticeInfo
	 */
	public function getNoticeById($id) {
		try {
			$db = $this->noticeDb;
			$notice = $db->getRecordById($id);
			return $notice;
		} catch (Exception $e) {
			parent::writeLog(__CLASS__, __METHOD__, $e);
			return null;
		}
	}

	public function insertNotice($date, $content) {
		try {
			$this->beginTransaction();
			$db = $this->noticeDb;
			$record = new Core_Models_NoticeInfo();
			$record->setContent($content);
			$date = str_replace("/", "-", $date);
			$record->setUpdateDate($date);

			$idUserLogin = Core_Util_Helper::getIdAdminLogin();
			$record->setUserId($idUserLogin);
			$id = $db->insertRecord($record);
			$this->commit();
			return $id > 0;
		} catch (Exception $e) {
			$this->rollBack();
			parent::writeLog(__CLASS__, __METHOD__, $e);
			return null;
		}
	}

	public function updateNotice($id, $date, $content) {
		try {
			$this->beginTransaction();
			$db = $this->noticeDb;
			$record = $db->getRecordById($id);
			$record->setContent($content);
			$date = str_replace("/", "-", $date);
			$record->setUpdateDate($date);
			$num = $db->updateRecord($record, array("notice_id = ?" => $id));
			$this->commit();
			return $num > 0;
		} catch (Exception $e) {
			$this->rollBack();
			parent::writeLog(__CLASS__, __METHOD__, $e);
			return null;
		}
	}
	
	public function deleteNotice($id, $idUpdate) {
		try {
			$this->beginTransaction();
			$db = $this->noticeDb;
			$record = $db->getRecordById($id);
			$num  = $db->deleteLogic($record, $idUpdate);
			$this->commit();
			return $num > 0;
		} catch (Exception $e) {
			$this->rollBack();
			parent::writeLog(__CLASS__, __METHOD__, $e);
			return null;
		}
	}

	public function checkNoticeDate($id, $date, $userId) {
		try {
			$db = $this->noticeDb;
			$isExist = $db->checkNoticeDate($id, $date, $userId);
			return $isExist;
		} catch (Exception $e) {
			parent::writeLog(__CLASS__, __METHOD__, $e);
			return false;
		}
	}
}
