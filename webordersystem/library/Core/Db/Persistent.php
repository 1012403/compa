<?php
abstract class Core_Db_Persistent extends Zend_Db_Table_Abstract {
	const FIELD_DATE_CREATED = 'add_date';
	const FIELD_DATE_ID = 'add_id';

	protected $_instanceClass;

	/**
	 * Select all record in table
	 * @param array $conditions
	 * @param string $order
	 * @param string $start
	 * @param string $count
	 * @return array object
	 */
	public function getAll($conditions = array(), $order = NULL, $offset = NULL,
			$count = NULL) {
		$select = $this->getQuery($conditions, $order);
		if ($offset !== NULL && $count !== NULL) {
			$select->limit($count, $offset);
		}

		if ($select instanceof Zend_Db_Table_Select) {
			$rows = $this->fetchAll($select);
		} else {
			$db = $this->getAdapter();
			$rows = $db->fetchAll($select);
		}

		$ret = $this->setRowsToArray($rows);

		return $ret;
	}

	/**
	 * get 1 record in table
	 * @param array $conditions, ex: array['col = ?' => val]
	 * @return object
	 */
	public function get($conditions = array()) {
		$select = $this->select()->from($this);
		foreach ($conditions as $key => $value) {
			$select->where($key, $value);
		}
		$row = $this->fetchRow($select);


		if (empty($row) == FALSE) {
			if (empty($this->_instanceClass) == FALSE) {
				return new $this->_instanceClass($row);
			}
		}
		return FALSE;
	}

	/**
	 * insertRecord
	 * @param Core_Models_Domain $record
	 * @param string $idInsert
	 * @return id inserted
	 */
	public function insertRecord($record, $idLogin = null) {
		$idInsert = $idLogin == null ? Core_Util_Helper::getIdAdminLogin() : $idLogin;
		if ($record instanceof Core_Models_Domain && $record !== null) {
			$data = $record->toArray();
		}

		//$data = $this->getRecordData($record, $idInsert);
		if ($record instanceof Core_Models_MasterModel && $record !== null) {
			$data = $this->setCommonInsertInfoToMstModel($data, $idInsert);
		}
		$priId = $this->insert($data);
		return $priId;
	}

	/**
	 * updateRecord
	 * @param Core_Models_Domain $record
	 * @param array $where
	 * @return number
	 */
	public function updateRecord($record, $where) {
		$idUpdate = Core_Util_Helper::getIdUserLogin();

		if ($record instanceof Core_Models_Domain && $record !== null) {
			$data = $record->toArray();
		}

		if ($record instanceof Core_Models_MasterModel && $record !== null) {
			$data = $this->setCommonUpdateInfoToMstModel($data, $idUpdate);
		}
		$numUpdated = $this->update($data, $where);
		return $numUpdated;
	}

	/**
	 * get query sql by condition
	 * @param array $conditions ()
	 * @param unknown $order
	 * @return Ambigous <Zend_Db_Table_Select, Zend_Db_Select, Zend_Db_Table_Select>
	 */
	protected function getQuery($conditions, $order) {
		$select = $this->select()->from($this);
		if ($conditions != null) {
			foreach ($conditions as $key => $value) {
				$select->where($key, $value);
			}
		}
		if ($order != NULL) {
			$select->order($order);
		}
		return $select;
	}

	/**
	 * Change array rows to object
	 * @param array $rows
	 * @return array of object
	 */
	protected function setRowsToArray($rows) {
		$ret = array();
		if (empty($this->_instanceClass) == FALSE) {
			foreach ($rows as $row) {
				$ret[] = new $this->_instanceClass($row);
			}
		}
		return $ret;
	}

	/**
	 * set field add_date to current
	 * @param unknown $record
	 * @param unknown $idInsert
	 * @return unknown
	 */
	protected function getRecordData($record, $idInsert) {
		//$record[self::FIELD_DATE_CREATED] = new Zend_Db_Expr('NOW()');
		//$record[self::FIELD_DATE_ID] = $idInsert;
		return $record;
	}


	/**
	 * set default info of field insert date and insert id
	 * @param array $record
	 * @param string $idInsert
	 * @return array
	 */
	protected function setCommonInsertInfoToMstModel($record, $idInsert) {
		if (is_array($record) && $record !== null) {
			$record[Core_Models_MasterModel::INSERT_DATE_FIELD]	= new Zend_Db_Expr('NOW()');
			$record[Core_Models_MasterModel::INSERT_ID_FIELD]	= $idInsert;
			$record[Core_Models_MasterModel::UPDATE_DATE_FIELD]	= new Zend_Db_Expr('NOW()');
			$record[Core_Models_MasterModel::UPDATE_ID_FIELD]	= $idInsert;
		}
		return $record;
	}

	/**
	 * set default info of field insert date and insert id
	 * @param array $record
	 * @param string $idInsert
	 * @return array
	 */
	protected function setCommonUpdateInfoToMstModel($record, $idUpdate) {
		if (is_array($record) && $record !== null) {
			$record[Core_Models_MasterModel::UPDATE_DATE_FIELD]	= new Zend_Db_Expr('NOW()');
			$record[Core_Models_MasterModel::UPDATE_ID_FIELD]	= $idUpdate;
		}
		return $record;
	}

	/**
	 * delete logic. Update delete_flg
	 * @return number
	 */
	public function deleteLogic($record, $idUpdate) {
		if ($record instanceof Core_Models_Domain && $record !== null) {

			$data = $record->toArray();

			if (is_array($this->_primary) == TRUE) {
				foreach ($this->_primary as $column) {
					$where[$column . ' = ?'] = $data[$column];
				}
			} else {
				$where[$this->_primary . ' = ?'] = $data[$this->_primary];
			}

			$data = array();
			$data[Core_Models_MasterModel::DELETE_FIELD] = Core_Models_MasterModel::DELETE_VAL;
			$data = $this->setCommonUpdateInfoToMstModel($data, $idUpdate);

			return $this->update($data, $where);
		}
	}

	/**
	 * deleteRecord
	 * @param Core_Models_Domain $record
	 * @return number
	 */
	public function deleteRecord($record){
		if ($record instanceof Core_Models_Domain && $record !== null) {

			$data = $record->toArray();

			if (is_array($this->_primary) == TRUE) {
				foreach ($this->_primary as $column) {
					$where[$column . ' = ?'] = $data[$column];
				}
			} else {
				$where[$this->_primary . ' = ?'] = $data[$this->_primary];
			}

			return $this->delete($where);
		}
	}

	/**
	 * getRecordById
	 * @param string $id
	 * @return
	 */
	public function getRecordById($id) {
		$data = $id;
		$primary = $this->_primary;
		if (is_array($primary) == TRUE) {
			foreach ($primary as $column) {
				$where[$column . ' = ?'] = $data[$column];
			}
		} else {
			$where[$primary . ' = ?'] = $data;
		}
		return $this->get($where);
	}


	/**
	 * get record to update
	 * @param array $record
	 * @return array
	 */
	protected function getRecordForUpdate($record) {
		return $record;
	}

	protected function prepair() {
	}
}
