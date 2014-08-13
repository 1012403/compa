<?php
class Core_Db_MailTemplateDb extends Core_Db_Persistent {
	protected $_name = Core_Util_TableNames::MAIL_TEMPLATE;

	protected $_primary = 'id';

	protected $_instanceClass = 'Core_Models_MailTemplate';

	/**
	 * getMailTemplate
	 * @param string $itemClass
	 * @param unknown $paginatorData
	 * @return multitype:
	 */
	public function getMailTemplate($itemClass=null, $paginatorData){

		$query = $this->select()->from(array("mail" =>$this->_name));
		$query->setIntegrityCheck(false);

		$query->joinInner(array("class" => Core_Util_TableNames::MST_CLASS),
				"mail.class = class.item_cd",
				array("class.item_name"));
		if ($itemClass!=null){
			$query->where("mail.class=?", $itemClass);
		}
		$query->where("class.item_type=?",Core_Util_Const::ADMIN_ITEM_TYPE);
		// ADD 20140425 Hieunm start
		$query->where('class.delete_flg = ?', Core_Util_Const::DELETE_FLG_0);
		// ADD 20140425 Hieunm end
		$query->order("mail.class DESC");

		//page
		if ($paginatorData['itemCountPerPage'] > 0) {
			$page     = $paginatorData['currentPage'];
			$rowCount = $paginatorData['itemCountPerPage'];
			$query->limitPage($page, $rowCount);
		}
		$row=$this->fetchAll($query)->toArray();
		return $row;
	}

	public function countEmailTemp($itempcd=null){
		$query = $this->select()->from(array("mail" =>$this->_name),array("count"=> "count(*)") );
		$query->setIntegrityCheck(false);

		$query->joinInner(array("mstclass" => Core_Util_TableNames::MST_CLASS),
				"mail.class = mstclass.item_cd",
				null);

		if ($itempcd!=null){
			$query->where("mail.class=?", $itempcd);
		}
		$query->where("mstclass.item_type=?",Core_Util_Const::ADMIN_ITEM_TYPE);
		// ADD 20140425 Hieunm start
		$query->where('mstclass.delete_flg = ?', Core_Util_Const::DELETE_FLG_0);
		// ADD 20140425 Hieunm end

		$rows = $this->fetchRow($query)->toArray();
		return $rows;
	}

	public function searchEmailTempById($id){
		$query = $this->select()->from(array("mail" =>$this->_name));
		$query->setIntegrityCheck(false);

		$query->joinInner(array("mstclass" => Core_Util_TableNames::MST_CLASS),
				"mail.class = mstclass.item_cd",
				array("mstclass.item_name"));

		$query->where("mail.id=?", $id);
		$query->where("mstclass.item_type=?",Core_Util_Const::ADMIN_ITEM_TYPE);
		// ADD 20140425 Hieunm start
		$query->where('mstclass.delete_flg = ?', Core_Util_Const::DELETE_FLG_0);
		// ADD 20140425 Hieunm end

		$row=$this->fetchRow($query)->toArray();
		return $row;
	}

	public function saveEmail($formData){
		$data=array(
			'class'=>$formData['class_itemp'],
			'title'=>trim($formData['title']),
			'header'=>trim($formData['header']),
			'footer'=>trim($formData['footer']),
			'apply_flg'=>$formData['apply_flg']
		);
		$result = $this->insert($data);
		return $result;
	}

	public function updateMail($id,$formData){
		$data=array(
			'title'=>trim($formData['title']),
			'header'=>trim($formData['header']),
			'footer'=>trim($formData['footer']),
			'apply_flg'=>$formData['apply_flg']
		);
		$where=("id=".$id);
		$result = $this->update($data, $where);
		return $result;
	}

	public function updateFlag($id){
		$flag=Core_Util_Const::APPLY_FLG_0;
		$data=array(
				'apply_flg'=>$flag
		);
		$where=("class= ".$id);
		$result = $this->update($data, $where);
		return $result;
	}

	/* public function getMailTemplateByItem($itemClass=null){
		$query = $this->select()->from(array("mail" =>$this->_name));
		$query->setIntegrityCheck(false);
		$query->where("class=?", $itemClass);
		$row=$this->fetchAll($query)->toArray();
		return $row;
	} */

	public function getMailTemplateByItem($itemClass=null,$flag=null){
		$query = $this->select()->from(array("mail" =>$this->_name));
		$query->setIntegrityCheck(false);
		$query->where("class=?", $itemClass);
		if ($flag!=null){
			$query->where("apply_flg=?", $flag);
		}
		$row=$this->fetchAll($query)->toArray();
		return $row;
	}
}




















