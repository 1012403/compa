<?php
class Core_Service_MstClassService extends Core_Service_Abstract {

	private $classDb;

	public function __construct() {
		parent::__construct();
		$this->classDb = new Core_Db_MstClassDb();
	}

	public function getMstClassByItemType($item_type){
		try {
			$db = $this->classDb;
			return $db->getMstClassByItemType($item_type);
		} catch (Exception $e) {
			parent::writeLog(__CLASS__, __METHOD__, $e);
			return FALSE;
		}
	}

	public function getMstClassByItemTypeDispl($item_type){
		try {
			$db = $this->classDb;
			return $db->getMstClassByItemTypeDispl($item_type);
		} catch (Exception $e) {
			parent::writeLog(__CLASS__, __METHOD__, $e);
			return FALSE;
		}
	}

	public function getMstClassByItemTypeAndItemCd($itemType, $itemCd) {
		try {
			$db = $this->classDb;
			return $db->getMstClassByItemTypeAndItemCd($itemType, $itemCd);
		} catch (Exception $e) {
			parent::writeLog(__CLASS__, __METHOD__, $e);
			return FALSE;
		}
	}

	public function getMstClassByItemTypeAndItemCdDispl($itemType, $itemCd) {
		try {
			$db = $this->classDb;
			return $db->getMstClassByItemTypeAndItemCdDispl($itemType, $itemCd);
		} catch (Exception $e) {
			parent::writeLog(__CLASS__, __METHOD__, $e);
			return FALSE;
		}
	}

	public function getItemName($itemType, $itemCd) {
		try {
			$db = $this->classDb;
			$mstClass = $db->getMstClassByItemTypeAndItemCd($itemType, $itemCd);
			if ($mstClass !== null && $mstClass !== false) {
				return $mstClass->getItemName();
			} else {
				return null;
			}
		} catch (Exception $e) {
			parent::writeLog(__CLASS__, __METHOD__, $e);
			return FALSE;
		}
	}

	public function getItemTypeNote1($itemType, $itemCd) {
		try {
			$db = $this->classDb;
			$mstClass = $db->getMstClassByItemTypeAndItemCd($itemType, $itemCd);
			if ($mstClass !== null && $mstClass !== false) {
				return $mstClass->getNote1();
			} else {
				return null;
			}
		} catch (Exception $e) {
			parent::writeLog(__CLASS__, __METHOD__, $e);
			return FALSE;
		}
	}

	public function getItemTypeNote2($itemType, $itemCd) {
		$db = $this->classDb;
		$mstClass = $db->getMstClassByItemTypeAndItemCd($itemType, $itemCd);
		if ($mstClass !== null && $mstClass !== false) {
			return $mstClass->getNote2();
		} else {
			return null;
		}
	}

	public function getItemTypeNote3($itemType, $itemCd) {
		$db = $this->classDb;
		$mstClass = $db->getMstClassByItemTypeAndItemCd($itemType, $itemCd);
		if ($mstClass !== null && $mstClass !== false) {
			return $mstClass->getNote3();
		} else {
			return null;
		}
	}

	public function getItemTypeNote4($itemType, $itemCd) {
		$db = $this->classDb;
		$mstClass = $db->getMstClassByItemTypeAndItemCd($itemType, $itemCd);
		if ($mstClass !== null && $mstClass !== false) {
			return $mstClass->getNote4();
		} else {
			return null;
		}
	}

	public function getItemTypeNote5($itemType, $itemCd) {
		$db = $this->classDb;
		$mstClass = $db->getMstClassByItemTypeAndItemCd($itemType, $itemCd);
		if ($mstClass !== null && $mstClass !== false) {
			return $mstClass->getNote5();
		} else {
			return null;
		}
	}

	public function getItemTypeNote6($itemType, $itemCd) {
		$db = $this->classDb;
		$mstClass = $db->getMstClassByItemTypeAndItemCd($itemType, $itemCd);
		if ($mstClass !== null && $mstClass !== false) {
			return $mstClass->getNote6();
		} else {
			return null;
		}
	}

	public function getMstClassUserType(){
		try {
			$db = $this->classDb;
			return $db->getMstClassUserType();
		} catch (Exception $e) {
			parent::writeLog(__CLASS__, __METHOD__, $e);
			return FALSE;
		}
	}

	public function getMstClassAdminType(){
		try {
			$db = $this->classDb;
			return $db->getMstClassAdminType();
		} catch (Exception $e) {
			parent::writeLog(__CLASS__, __METHOD__, $e);
			return FALSE;
		}
	}

	/**
	 * getMstClassByCondition
	 * @return boolean
	 */
	public function getMstClassByCondition($condition){
		try {
			$db = $this->classDb;
			return $db->getMstClassByCondition($condition);
		} catch (Exception $e) {
			parent::writeLog(__CLASS__, __METHOD__, $e);
			return FALSE;
		}
	}

	// ADD 20140516 Hieunm start
	/**
	 * getMstClassByCondition
	 * @return boolean
	 */
	public function getMstClassByItemTypeAndItemCdForUpdate($itemType, $itemCd){
		try {
			$db = $this->classDb;
			return $db->getMstClassByItemTypeAndItemCdForUpdate($itemType, $itemCd);
		} catch (Exception $e) {
			parent::writeLog(__CLASS__, __METHOD__, $e);
			return FALSE;
		}
	}
	// ADD 20140516 Hieunm end

	/**
	 * updateMstClass
	 * @return boolean
	 */
	public function updateMstClass(Core_Models_MstClass $mstClass){
		try {
			$db = $this->classDb;
			$this->beginTransaction();
			$ret = $db->updateMstClass($mstClass);
			$this->commit();
			return $ret;
		} catch (Exception $e) {
			parent::writeLog(__CLASS__, __METHOD__, $e);
			$this->rollBack();
			return FALSE;
		}
	}

	/**
	 * insertMstClass
	 * @return boolean
	 */
	public function insertMstClass(Core_Models_MstClass $mstClass){
		try {
			$db = $this->classDb;
			$this->beginTransaction();
			/*$nextItemCd = $db->getNextItemCd($mstClass->getItemType());
			$mstClass->setItemCd($nextItemCd);*/

			$ret = $db->insertRecord($mstClass);
			$this->commit();
			return $ret;
		} catch (Exception $e) {
			parent::writeLog(__CLASS__, __METHOD__, $e);
			$this->rollBack();
			return FALSE;
		}
	}

	/**
	 * insertMstClass
	 * @return boolean
	 */
	public function insertArrMstClass($arrMstClass){
		try {
			$db = $this->classDb;
			$this->beginTransaction();
			/* @var $mstClass Core_Models_MstClass */
			foreach ($arrMstClass as $key => $mstClass) {
				$db->insertRecord($mstClass);
			}
			$this->commit();
			return true;
		} catch (Exception $e) {
			parent::writeLog(__CLASS__, __METHOD__, $e);
			$this->rollBack();
			return FALSE;
		}
	}

	/**
	 * deleteMstClass
	 * @return boolean
	 */
	public function deleteMstClass($arrId){
		try {
			$db = $this->classDb;
			$this->beginTransaction();
			foreach ($arrId as $id){
				if($id !=''){
					$db->deleteMstClass($id);
				}
			}
			$this->commit();
			return true;
		} catch (Exception $e) {
			parent::writeLog(__CLASS__, __METHOD__, $e);
			$this->rollBack();
			return FALSE;
		}
	}

	/**
	 * deleteMstClass
	 * @return boolean
	 */
	public function getTax(){
		try {
			$db = $this->classDb;
			$tax = $db->getTax();
			return Core_Util_Helper::nullToZero($tax);
		} catch (Exception $e) {
			parent::writeLog(__CLASS__, __METHOD__, $e);
		}
	}

	public function checkIdExist($id){
		try {
			$db = $this->classDb;
			$mstClass = $db->getRecordById($id);
			return $mstClass !== null && $mstClass !== false;
		} catch (Exception $e) {
			parent::writeLog(__CLASS__, __METHOD__, $e);
		}
	}
	
	// ADD 20140703 Locpht start
	/**
	 * checkMstClassByItemTypeAndItemCd
	 * @return count
	 */
	public function checkMstClassByItemTypeAndItemCd($itemType, $itemCd){
		try {
	      $db = $this->classDb;
	      $countclass = $db->checkMstClassByItemTypeAndItemCd($itemType, $itemCd);
	      return Core_Util_Helper::getDataRow($countclass, 'count');;
	    } catch (Exception $e) {
	      parent::writeLog(__CLASS__, __METHOD__, $e);
	      return 0;
	    }
	}
	// ADD 20140703 Locpht end

}


