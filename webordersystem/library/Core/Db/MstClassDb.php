<?php
class Core_Db_MstClassDb extends Core_Db_Persistent {

	protected $_name = Core_Util_TableNames::MST_CLASS;

	protected $_primary = 'id';
	//protected $_primary = array('item_type', 'item_cd');	// Add 20140515 Hieunm

	protected $_instanceClass = 'Core_Models_MstClass';

	public function getMstClassByItemType($item_type) {
		$where = array();
		$where['item_type LIKE ?'] = $item_type;
		// ADD 20140424 Hieunm start
		$where ['delete_flg = ?'] = Core_Util_Const::DELETE_FLG_0;
		// ADD 20140424 Hieunm end
		$order= 'item_order ASC';
		$arrMstClass = $this->getAll($where, $order);
		return $arrMstClass;
	}

	public function getMstClassByItemTypeDispl($item_type) {
		$where = array();
		$where['item_type LIKE ?'] = $item_type;
		// ADD 20140424 Hieunm start
		$where ['delete_flg = ?'] = Core_Util_Const::DELETE_FLG_0;
		// ADD 20140424 Hieunm end
		$where ['display_flg = ?'] = '1';
		$order= 'item_order ASC';
		$arrMstClass = $this->getAll($where, $order);
		return $arrMstClass;
	}

	/**
	 *
	 * @param unknown $itemType
	 * @param unknown $itemCd
	 * @return Core_Models_MstClass
	 */
	public function getMstClassByItemTypeAndItemCd($itemType, $itemCd) {
		$where = array();
		$where['item_type LIKE ?'] = $itemType;
		$where['item_cd = ?'] = $itemCd;
		// ADD 20140424 Hieunm start
		$where ['delete_flg = ?'] = Core_Util_Const::DELETE_FLG_0;
		// ADD 20140424 Hieunm end
		$arrMstClass = $this->get($where);
		return $arrMstClass;
	}
	
	/**
	 *
	 * @param unknown $itemType
	 * @param unknown $itemCd
	 * @return Core_Models_MstClass
	 */
	public function getMstClassByItemTypeAndItemName($itemType, $itemName) {
		$where = array();
		$where['item_type LIKE ?'] = $itemType;
		$where['item_name LIKE ?'] = $itemName;
		$where ['delete_flg = ?'] = Core_Util_Const::DELETE_FLG_0;
		$arrMstClass = $this->get($where);
		return $arrMstClass;
	}

	public function getMstClassByItemTypeAndItemCdDispl($itemType, $itemCd) {
		$where = array();
		$where['item_type LIKE ?'] = $itemType;
		$where['item_cd = ?'] = $itemCd;
		// ADD 20140424 Hieunm start
		$where ['delete_flg = ?'] = Core_Util_Const::DELETE_FLG_0;
		$where ['display_flg = ?'] = "1";
		// ADD 20140424 Hieunm end
		$arrMstClass = $this->get($where);
		return $arrMstClass;
	}

	// ADD 20140516 Hieunm start
	public function getMstClassByItemTypeAndItemCdForUpdate($itemType, $itemCd) {
		$where = array();
		$where['item_type = ?'] = $itemType;
		$where['item_cd = ?'] = $itemCd;
		$where['delete_flg = ?'] = Core_Util_Const::DELETE_FLG_0;
		$arrMstClass = $this->get($where);
		return $arrMstClass;
	}
	// ADD 20140516 Hieunm end

	public function getMstClassUserType() {
		$where = array();
		$where['item_type LIKE ?'] = Core_Util_Const::USER_TYPE;
		// ADD 20140424 Hieunm start
		$where ['delete_flg = ?'] = Core_Util_Const::DELETE_FLG_0;
		// ADD 20140424 Hieunm end
		$order= 'item_order ASC';
		$arrMstClass = $this->getAll($where, $order);
		return $arrMstClass;
	}

	public function getMstClassAdminType() {
		$where = array();
		$where['item_type LIKE ?'] = Core_Util_Const::ADMIN_TYPE;
		// ADD 20140424 Hieunm start
		$where ['delete_flg = ?'] = Core_Util_Const::DELETE_FLG_0;
		// ADD 20140424 Hieunm end
		$order= 'item_order ASC';
		$arrMstClass = $this->getAll($where, $order);
		return $arrMstClass;
	}

	/**
	 * getMstClassByCondition Admin
	 */
	public function getMstClassByCondition($condition){
		$where = array();
		$order = array();

		$where['item_type LIKE ?'] = $condition['item_type'];
		// ADD 20140424 Hieunm start
		$where ['delete_flg = ?'] = Core_Util_Const::DELETE_FLG_0;
		// ADD 20140424 Hieunm end
		if($condition['condition1'] != '' &&  $condition['valueCondition1'] != ''){
			$where[$condition['condition1'].' like "%?%"'] = new Zend_Db_Expr($condition['valueCondition1']);
		}

		if($condition['condition2'] != '' &&  $condition['valueCondition2'] != ''){
			$where[$condition['condition2'].' like "%?%"'] = new Zend_Db_Expr($condition['valueCondition2']);
		}

		$order= 'item_order ASC';

		$arrMstClass = $this->getAll($where, $order);
		return $arrMstClass;
	}

	/**
	 * updateMstClass
	 * @param Core_Models_MstClass $mstClass
	 * @return number
	 */
	public function updateMstClass(Core_Models_MstClass $mstClass){
		$data = array();
		$data['item_cd']    = $mstClass->getItemCd();
		$data['item_name']    = $mstClass->getItemName();
		$data['item_order']    = $mstClass->getItemOrder();
		$data['display_flg']    = $mstClass->getDisplayFlg();
		$data['note1']    = $mstClass->getNote1();
		$data['note2']    = $mstClass->getNote2();
		$data['note3']    = $mstClass->getNote3();
		$data['note4']    = $mstClass->getNote4();
		$data['note5']    = $mstClass->getNote5();
		// ADD 20140424 Hieunm start
		$data['update_ymd'] = new Zend_Db_Expr('NOW()');
		// ADD 20140424 Hieunm end
		$where = array();
		// MOD 20140515 Hieunm start
		$where['id= ?'] = $mstClass->getId();
		//$where['item_cd = ?'] = $mstClass->getItemCd();
		//$where['item_type = ?'] = $mstClass->getItemType();
		// MOD 20140515 Hieunm end
		// ADD 20140424 Hieunm start
		$where ['delete_flg = ?'] = Core_Util_Const::DELETE_FLG_0;
		// ADD 20140424 Hieunm end
		return $this->update($data, $where);
	}

	/**
	 * getNextItemCd of ItemType
	 * @param unknown $contactId
	 * @return Ambigous <boolean, id, mixed, multitype:>
	 */
	public function getNextItemCd($itemType) {
		$query = $this->select()->from(array("mstClass" =>$this->_name), array('max(mstClass.item_cd) as maxValue'));
		$query->setIntegrityCheck(false);
		$query->where('mstClass.item_type LIKE ? ', $itemType);

		$rows = $this->fetchRow($query);

		return ($rows->maxValue + 1);
	}

	/**
	 * deleteMstClass
	 * @return unknown
	 */
	public function deleteMstClass($id){
		$ret = null;
		// MOD 20140424 Hieunm start
		//$where['id = ?'] = $id;
		//$ret = $this->delete($where);
		//return $ret;
		$data = array();
		$data['update_ymd'] = new Zend_Db_Expr('NOW()');
		$data['delete_flg'] = '1';
		$where['id = ?'] = $id;
		return $this->update($data, $where);
		// MOD 20140424 Hieunm end
	}

	public function getTax() {
		$query = $this->select()
		->from(array("cls" =>$this->_name),
				array('cls.note2 as tax'));
		$query->setIntegrityCheck(false);
		$query->where('cls.item_type LIKE ? ', Core_Util_Const::ITEM_TYPE_TAX);
		// ADD 20140424 Hieunm start
		$query->where('cls.delete_flg = ? ', Core_Util_Const::DELETE_FLG_0);
		// ADD 20140424 Hieunm end

		$expression = "";
		$expression .= " cls.note1 in ( SELECT max(t.note1) FROM " . Core_Util_TableNames::MST_CLASS . " t";
		$expression .= " WHERE ";
		$expression .= " t.item_type LIKE '" . Core_Util_Const::ITEM_TYPE_TAX . "' ";
		$expression .= " AND STR_TO_DATE(t.note1, '%Y-%m-%d') <= NOW() ) ";
		$innerWhere = new Zend_Db_Expr($expression);

		$query->where($innerWhere);

		$row = $this->fetchRow($query);
		if ($row === null) {
			return 0;
		} else {
			return $row->tax;
		}
	}
	
	// ADD 20140703 Locpht start
	public function checkMstClassByItemTypeAndItemCd($itemType, $itemCd) {
		$select = $this->select()->setIntegrityCheck ( false )
		->from(array("ml"=>$this->_name), array("count"=>"count(*)"))
		->where("ml.item_type = ?",$itemType)
		->where("ml.item_cd = ?",$itemCd)
		->where("ml.delete_flg = ?",Core_Util_Const::DELETE_FLG_0);
		
		$rows = $this->fetchRow( $select );
		return $rows;
	}
	// ADD 20140703 Locpht end
}