<?php
/**
 * 
 * @author nhanlt
 *
 */
class Core_Db_NoticeInfoDb extends Core_Db_Persistent {
	protected $_name = Core_Util_TableNames::NOTICE_INFO;
	protected $_primary = 'notice_id';
	protected $_instanceClass = 'Core_Models_NoticeInfo';

	/**
	 * getNews
	 * @return Core_Models_NoticeInfo
	 */
	public function getNews() {
		$expression = "";
		$expression .= " SELECT ";
		$expression .= " MAX(t2.update_date) AS update_date ";
		$expression .= " FROM NOTICE_INFO t2 ";
		$expression .= " WHERE ";
		$expression .= " t2.user_id IN ( ";
		$expression .= " SELECT t3.user_id  ";
		$expression .= " FROM MST_USER t3 ";
		$expression .= " WHERE t3.admin_class = '".Core_Util_Const::ADMIN_TYPE_SYSTEM_ADMINISTRATOR."' ";
		$expression .= " ) ";
		$expression .= " AND t2.update_date < NOW() ";
		$expression .= " AND t2.delete_flg = '0' ";
		
		$whereCondition = new Zend_Db_Expr($expression);
		
		$select = $this->select()->setIntegrityCheck ( false )
		->from(array("t" => $this->_name))
		->where("t.update_date IN (?)", $whereCondition);
		$rows = $this->fetchAll($select);
		$data = $this->setRowsToArray($rows);
		
		if (count($data) > 0) {
			return $data[0];
		} else {
			return null; 
		}
	}
	
	/**
	 * 
	 * @param unknown $userId
	 * @return Ambigous <unknown>|NULL
	 */
	public function getNoticeOfSale($userId) {
		$expression = "";
		$expression .= " SELECT  ";
		$expression .= " max(t.update_date) as update_date ";
		$expression .= " FROM NOTICE_INFO t ";
		$expression .= " WHERE  ";
		$expression .= " t.user_id = '" . $userId . "' ";
		$expression .= " AND t.update_date < NOW()  ";
		$expression .= " AND t.delete_flg = '0' ";
		
		$whereCondition = new Zend_Db_Expr($expression);
		
		$select = $this->select()->setIntegrityCheck ( false )
		->from(array("t" => $this->_name))
		->where("t.update_date IN (?)", $whereCondition)
		->where("t.user_id = ?", $userId);
		$rows = $this->fetchAll($select);
		$data = $this->setRowsToArray($rows);
		
		if (count($data) > 0) {
			return $data[0];
		} else {
			return null;
		}
	}
	
	public function getListNoticeOfSaleUser($userId) {
		$select = $this->select()
		->from(array("t" => $this->_name),
				array(
						"notice_id" => "t.notice_id",
						"user_id" => "t.user_id",
						"update_date" => "DATE_FORMAT(t.update_date,'%Y/%m/%d')",
						"content" => "content",
						"delete_flg" => "delete_flg",
						"insert_ymd" => "insert_ymd",
						"update_ymd" => "update_ymd",
						"insert_user_id" => "insert_user_id",
						"update_user_id" => "update_user_id"
						)
		
		)
		->where("t.delete_flg = ?", '0')
		->where("t.user_id = ?", $userId)
		->order("t.update_date DESC")
		;
		$rows = $this->fetchAll($select);
		$data = $this->setRowsToArray($rows);
		return $data;
	}
	
	public function checkNoticeDate($id, $date, $userId) {
		$date = str_replace("/", "-", $date);
		$select = $this->select()
		->from(array("t" => $this->_name))
		->where("t.delete_flg = ?", '0')
		->where("t.update_date = ?", $date)
		->where("t.user_id = ?", $userId);
		if (!Core_Util_Helper::isEmpty($id)) {
			$select->where("t.notice_id != ?", $id);
		}
			
		$rows = $this->fetchAll($select);
		$data = $this->setRowsToArray($rows);
		return count($data) > 0;
	}
}
