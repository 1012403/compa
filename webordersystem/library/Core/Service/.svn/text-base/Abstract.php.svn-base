<?php
abstract class Core_Service_Abstract {
	protected $db;

	protected $option;

	private $isInTransaction;

	public function __construct() {
		$this->db = Core_Db_Adapter::getAdapter();
		$this->option = array(Zend_Db_Table_Abstract::ADAPTER => $this->db);

		$this->isInTransaction = FALSE;
	}

	/**
	 * get db adater
	 * @return mixed
	 */
	protected function getAdapter() {
		return $this->db;
	}

	/**
	 * begin transaction
	 */
	protected function beginTransaction() {
		if ($this->isInTransaction == FALSE) {
			$this->db->beginTransaction();
			$this->isInTransaction = TRUE;
		}
	}

	/**
	 * commit transaction
	 */
	protected function commit() {
		if ($this->isInTransaction == TRUE) {
			$this->db->commit();
			$this->isInTransaction = FALSE;
		}
	}

	/**
	 * rollback transaction
	 */
	protected function rollBack() {
		if ($this->isInTransaction == TRUE) {
			$this->db->rollBack();
			$this->isInTransaction = FALSE;
		}
	}

	/**
	 * check is in transaction
	 * @return boolean
	 */
	protected function isInTransaction() {
		return $this->isInTransaction;
	}

	/**
	 *
	 * @param string $classname
	 * @param string $methodName
	 * @param Exception $e
	 */
	protected function writeLog($classname, $methodName, Exception $e) {
		echo "class: " . $classname . "<br />";
		echo "method: " . $methodName . "<br />";
		echo "line: ". $e->getLine() . "<br />";
		echo "Message: " . $e->getMessage(). "<br />";
		echo "Trace : " . $e->getTraceAsString();
		die();
	}

}
