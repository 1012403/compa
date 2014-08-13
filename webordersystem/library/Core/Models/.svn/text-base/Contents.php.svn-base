<?php
class Core_Models_Contents extends Core_Models_Domain {
	private $id;

	private $name;

	private $content;

	public function __construct($data = null) {
		if ($data != null) {
			if ($data instanceof Zend_Db_Table_Row_Abstract
			|| is_array($data) == TRUE) {
				$this->id        = $this->getData($data, 'id');
				$this->name      = $this->getData($data, 'name');
				$this->content         = $this->getData($data, 'content');
			}
		}
	}

	public function toArray() {
		$arr = parent::toArray();
		$arr["id"] = $this->id;
		$arr["name"] = $this->name;
		$arr["content"] = $this->content;
		return $arr;
	}

	public function getId() {
		return $this->id;
	}

	public function setId($id) {
		$this->id = $id;
	}

	public function getContent() {
		return $this->content;
	}

	public function setContent($content) {
		$this->content = $content;
	}

	public function getName() {
		return $this->name;
	}

	public function setName($namet) {
		$this->name = $namet;
	}

}