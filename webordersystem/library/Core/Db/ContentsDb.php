<?php
class Core_Db_ContentsDb extends Core_Db_Persistent {

	protected $_name = 'contents';

	protected $_primary = 'id';

	protected $_instanceClass = 'Core_Models_Contents';

	/**
	 *
	 * @return array
	 */
	public function getContentGroupByType() {
		$query = $this->select()->from(array("ct" => $this->_name));
		$query->order("ct.contents_type");
		$query->order("ct.pattern_no");
		$rows = $this->fetchAll($query);
		$res = $this->setRowsToArray($rows);
		return $res;
	}

	public function delContentById($contents_id, $idLogin) {

    	$data = array();
    	$data['message'] = NULL;
		$data['profile_image_path'] = NULL;
		$data['add_id']     = $idLogin;
		$data['add_date']   = new Zend_Db_Expr('NOW()');
		$data['rec_id']     = $idLogin;
		$data['rec_date']   = new Zend_Db_Expr('NOW()');
		$ret = FALSE;

		$where['contents_id = ?'] = $contents_id;
		//$ret = $this->delete($where);
		$ret = $this->update($data, $where);
		return $ret;
    }
}