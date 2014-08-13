<?php
class Core_Db_MstUserShippingDb extends Core_Db_Persistent {

	protected $_name =  Core_Util_TableNames::MST_USER_SHIPPING;
	protected $_primary = array('user_id', 'shipping_seq');
	protected $_instanceClass = 'Core_Models_MstUserShipping';

	/**
	 *
	 * @param int $userId
	 * @return Core_Models_MstUser
	 */
	public function getUserShippingById($userId, $shippingSeq = null) {
		$where = array ();
		$where ['user_id  = ?'] = $userId;
		// ADD 20140512 Hieunm start
		if ($shippingSeq !== null) {
			 $where ['shipping_seq  = ?'] = $shippingSeq;
		}
		// ADD 20140512 Hieunm end
		$usershipping = $this->getAll ( $where );
		return $usershipping;
	}

	/**
	 * 
	 * @param unknown $userId
	 * @param unknown $idSeq
	 * @return Core_Models_MstUserShipping
	 */
	public function getShipping($userId, $idSeq) {
		$where = array ();
		$where ['user_id  = ?'] = $userId;
		$where ['shipping_seq  = ?'] = $idSeq;
		$usershipping = $this->get ( $where );
		return $usershipping;
	}
	
	public function nextSeq($userId) {
		$select = $this->select();
        $select->from($this, array('MAX(shipping_seq) + 1 as nxtseq'));
        $select->where('user_id  = ?', $userId);
        
        $rows = $this->fetchAll($select);
       
        return(Core_Util_Helper::nullToZero($rows[0]->nxtseq));
	}
	
	public function seqShipping($userId) {
		$select = $this->select();
		$select->from($this, array('MAX(shipping_seq) as seq'));
		$select->where('user_id  = ?', $userId);
	
		$rows = $this->fetchAll($select);
		return(Core_Util_Helper::nullToZero($rows[0]->seq));
	}
	
	
	/**
	 * insertShippingUser
	 * @param Core_Models_MstUserShipping $mstUser
	 */
	public function insertShippingUser($mstShipping){
		$nextSep = $this->seqShipping($mstShipping->getUserId());
		$mstShipping->setShippingSeq($nextSep + 1);
		$result = $this->insertRecord($mstShipping);
		return $result;
		
		
	}
}
