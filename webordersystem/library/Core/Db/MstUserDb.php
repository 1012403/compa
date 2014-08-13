<?php
class Core_Db_MstUserDb extends Core_Db_Persistent {
	protected $_name =  Core_Util_TableNames::MST_USER;
	protected $_primary = 'user_id';
	protected $_instanceClass = 'Core_Models_MstUser';

	/**
	 *
	 * @param Core_Models_MstUser $staff
	 * @return Core_Models_MstUser
	 */
	public function authorize(Core_Models_MstUser $staff) {
		$where = array ();
		$where ['login_username  = ?']		= $staff->getLoginUsername ();
		$where ['login_password = MD5(?)']	= $staff->getLoginPassword ();

		//add start nguyenpth 2014/04/23
		$where ['delete_flg  = ?']		= Core_Util_Const::DELETE_FLG_0;
		//add end nguyenpth 2014/04/23

		$user = $this->get ( $where );
		return $user;
	}
	
	/**
	 *authorizeSession
	 * @param Core_Models_MstUser $staff
	 * @return Core_Models_MstUser
	 */
	public function authorizeSession(Core_Models_MstUser $staff) {
		$where = array ();
		$where ['login_username  = ?']		= $staff->getLoginUsername ();
		$where ['login_password = ?']	= $staff->getLoginPassword ();
	
		//add start nguyenpth 2014/04/23
		$where ['delete_flg  = ?']		= Core_Util_Const::DELETE_FLG_0;
		//add end nguyenpth 2014/04/23
	
		$user = $this->get ( $where );
		return $user;
	}


	/**
	 *loginAdmin
	 * @param Core_Models_MstUser $staff
	 * @return loginAdmin
	 */
	public function loginAdmin(Core_Models_MstUser $staff) {
		$where = array ();
		$where ['login_username  = ?']		= $staff->getLoginUsername ();
		$where ['login_password = MD5(?)']	= $staff->getLoginPassword ();
		$where['admin_class != ?']			= Core_Util_Const::ADMIN_TYPE_NO;

		//add start nguyenpth 2014/04/23
		$where ['delete_flg  = ?']		= Core_Util_Const::DELETE_FLG_0;
		//add end nguyenpth 2014/04/23

		$user = $this->get ( $where );
		return $user;
	}
	
	/**
	 *loginAdminSession
	 * @param Core_Models_MstUser $staff
	 * @return loginAdminSession
	 */
	public function loginAdminSession(Core_Models_MstUser $staff) {
		$where = array ();
		$where ['login_username  = ?']		= $staff->getLoginUsername ();
		$where ['login_password = ?']	= $staff->getLoginPassword ();
		$where['admin_class != ?']			= Core_Util_Const::ADMIN_TYPE_NO;
	
		//add start nguyenpth 2014/04/23
		$where ['delete_flg  = ?']		= Core_Util_Const::DELETE_FLG_0;
		//add end nguyenpth 2014/04/23
	
		$user = $this->get ( $where );
		return $user;
	}
	/**
	 *
	 * @param unknown $username
	 * @param unknown $token
	 * @return Ambigous <object, unknown, boolean>
	 */
	public function checkToken($username, $token) {
		$where = array ();
		$where ['login_username  = ?'] = $username;
		$where ['change_pass_token= ? '] = $token;
		// ADD 20140424 Hieunm start
		$where ['delete_flg = ?']		 = Core_Util_Const::DELETE_FLG_0;
		// ADD 20140424 Hieunm end
		$user = $this->get ( $where );
		return $user;
	}

	public function checkEmail($username, $email) {
		$where = array ();
		$where ['login_username  = ?'] = $username;
		$where ['email= ? '] = $email;
		// ADD 20140424 Hieunm start
		$where ['delete_flg = ?'] = Core_Util_Const::DELETE_FLG_0;
		// ADD 20140424 Hieunm end
		$user = $this->get ( $where );
		return $user;
	}

	/**
	 *
	 * @param unknown $username
	 * @return Core_Models_MstUser
	 */
	public function getUserByUsername($username) {
		$where = array ();
		$where ['login_username  = ?'] = $username;

		//add start nguyenpth 2014/04/23
		$where ['delete_flg  = ?']		= Core_Util_Const::DELETE_FLG_0;
		//add end nguyenpth 2014/04/23

		$user = $this->get ( $where );
		return $user;
	}
	
	public function getUserIdByUserNAme($username){
		$select = $this->select()->setIntegrityCheck ( false )
		->from(array("mu"=>$this->_name),array("user_id"=>"mu.user_id"));
	
		$select->where('trim(mu.login_username) collate utf8_unicode_ci = ?', $username);
		$select->where('mu.delete_flg  = ?', Core_Util_Const::DELETE_FLG_0);
		
		$rows = $this->fetchRow( $select );
		$idCategory = Core_Util_Helper::getDataRow($rows, 'user_id');
		return Core_Util_Helper::nullToZero($idCategory);
	
	}
	
	/**
	 *
	 * @param unknown $cookie
	 * @return getUserByCookie
	 */
	public function getUserByCookie($cookie) {
		$where = array ();
		$where ['token_cookie = ?'] = $cookie;
		//$where=("token_cookie=".$cookie);
		//add start nguyenpth 2014/04/23
		$where ['delete_flg  = ?']	= Core_Util_Const::DELETE_FLG_0;
		//add end nguyenpth 2014/04/23

		$user = $this->get ( $where );
		return $user;
	}

	public function getAdminByCookie($cookie) {
		$where = array ();
		$where ['token_cookie_admin  = ?'] = $cookie;
		//$where=("token_cookie=".$cookie);

		//add start nguyenpth 2014/04/23
		$where ['delete_flg  = ?']	= Core_Util_Const::DELETE_FLG_0;
		//add end nguyenpth 2014/04/23

		$user = $this->get ( $where );
		return $user;
	}

	/**
	 *
	 * @param unknown $dataArr
	 * @return number
	 */
	 public function updateTokenDateSendlink($username,$token) {
		$where ['login_username = ?'] = $username;

		//add start nguyenpth 2014/04/23
		$where ['delete_flg  = ?']	= Core_Util_Const::DELETE_FLG_0;
		//add end nguyenpth 2014/04/23

		$chageDate = new Zend_Db_Expr ( 'NOW()' );
		$user = $this->getUserByUsername($username);


		$result = false;
		if ($user !== null && $user !== false) {

			$user->setChangePassToken($token);
			$user->setChangePassDate($chageDate);

			//add start nguyenpth 2014/04/23
			$user->setUpdateYmd($chageDate);
			
			$result = $this->updateRecord($user, $where);
		}
		return $result;
	}


	/**
	 * change password
	 * @param string $username
	 * @param string $password
	 * @return boolean
	 */
	public function changePasswd($username, $password) {
		$where ['login_username = ?'] = $username;

		//add start nguyenpth 2014/04/23
		$where ['delete_flg  = ?']	= Core_Util_Const::DELETE_FLG_0;
		$chageDate = new Zend_Db_Expr ( 'NOW()' );
		//add end nguyenpth 2014/04/23

		$result = false;
		$numUpdated = 0;
		$user = $this->getUserByUsername($username);

		if ($user !== null && $user !== false) {
			// encrypt password
			$password = Core_Util_Helper::encryptPassword($password);

			//add start nguyenpth 2014/04/23
			$idUser=$user->getUserId();
			//add end nguyenpth 2014/04/23

			$user->setLoginPassword($password);
			$user->setChangePassDate(null);
			$user->setChangePassToken(null);

			//add start nguyenpth 2014/04/23
			$user->setUpdateYmd($chageDate);
			$user->setUpdateUserId($idUser);
			//add end nguyenpth 2014/04/23

			$numUpdated = $this->updateRecord($user, $where);
		}

		return $numUpdated > 0;

	}

	/**
	 *
	 * @param unknown $date
	 * @return boolean
	 */
	public function checkPasswdDate( $date) {
		$now=getdate();
		$currentDate = $now["mday"] . "." . $now["mon"] . "." . $now["year"];
		if (strtotime($currentDate)-strtotime($date)< 10*24*3600 ) {
			return true;
		}else
			return false;
	}

	public function queryAllUserManager() {
		$select = $this->select()->setIntegrityCheck ( false )->distinct()
		->from(array("mu"=>$this->_name), array("*"))
		->where("mu.delete_flg = 0")
		->where("mu.user_class = ?",Core_Util_Const::USER_TYPE_SALES_REPRESENTATIVE)
		->where("mu.admin_class <> ?",Core_Util_Const::ADMIN_TYPE_NO);
		$rows = $this->fetchAll ( $select );
		return $this->setRowsToArray($rows);
	}

	public function queryAllUser($paginatorData, $user_name=null, $area_code=null, $tel_no=null, $sales_id=null) {
		$select = $this->select()->setIntegrityCheck ( false )->distinct()
		->from(array("mu"=>$this->_name), array("*"))
		->where("mu.delete_flg = 0");

		/*$select->joinLeft(array("mus" => Core_Util_TableNames::MST_USER_SHIPPING),
				"mus.user_id = mu.user_id",
				array("tel_no"=>"mus.tel_no"));*/

		$select->joinLeft(array("mc1" => Core_Util_TableNames::MST_CLASS),
					$this->_db->quoteInto(
							"mu.user_class = mc1.item_cd and mc1.delete_flg = '0' and mc1.item_type LIKE ?", Core_Util_Const::USER_TYPE
					), array("user_type_name"=>"mc1.item_name"));
		$select->joinLeft(array("mc2" => Core_Util_TableNames::MST_CLASS),
					$this->_db->quoteInto(
							"mu.admin_class = mc2.item_cd and mc2.delete_flg = '0' and mc2.item_type LIKE ?", Core_Util_Const::ADMIN_TYPE
					), array("admin_type_name"=>"mc2.item_name"));

		$select->joinLeft(array("mu1" => Core_Util_TableNames::MST_USER),
					"mu1.user_id = mu.sales_id", array("sales_name"=>"mu1.user_name"));

		if ($user_name != null){
			$select->where("mu.user_name like ?", "%$user_name%" );
		}
		if ($area_code != null){
			$select->where("mu.area_code = ?", $area_code );
		}
		if ($tel_no != null){
			$select->where("mu.tel_no like ?", "%$tel_no%" );
		}
		if ($sales_id != null){
			$select->where("mu.sales_id = ?", $sales_id );
		}

		$select->order("mu.login_username");

		if ($paginatorData['itemCountPerPage'] > 0) {
			$page     = $paginatorData['currentPage'];
			$rowCount = $paginatorData['itemCountPerPage'];
			$select->limitPage($page, $rowCount);
		}
		
		$rows = $this->fetchAll ( $select )->toArray();
		return $rows;
	}

	/**
	 * queryAllUserForExport
	 * @param string $user_name
	 * @param string $area_code
	 * @param string $tel_no
	 * @param string $sales_id
	 * @return multitype:
	 */
	public function queryAllUserForExport( $user_name=null, $area_code=null, $tel_no=null, $sales_id=null) {
		$select = $this->select()->setIntegrityCheck ( false )->distinct()
		->from(array("mu"=>$this->_name), array("*"))
		->where("mu.delete_flg = 0");
	
		$select->joinLeft(array("mus" => Core_Util_TableNames::MST_USER_SHIPPING),
				"mus.user_id = mu.user_id", 
				array(
					"mus.shipping_seq as shipping_seq",
					"mus.shipping_des_name as shipping_des_name",
					"mus.post_no as shipping_post_no",
					"mus.address1 as shipping_address1",
					"mus.address2 as shipping_address2",
					"mus.tel_no as shipping_tel_no",
					"mus.fax_no as shipping_fax_no",
					"mus.trans_type as shipping_trans_type"
				));
		
		$select->joinLeft(array("mc1" => Core_Util_TableNames::MST_CLASS),
				$this->_db->quoteInto(
					"mu.user_class = mc1.item_cd and mc1.delete_flg = '0' and mc1.item_type LIKE ?", Core_Util_Const::USER_TYPE
				), array("mc1.item_name as user_type_name"));
		
		$select->joinLeft(array("mc2" => Core_Util_TableNames::MST_CLASS),
				$this->_db->quoteInto(
					"mu.admin_class = mc2.item_cd and mc2.delete_flg = '0' and mc2.item_type LIKE ?", Core_Util_Const::ADMIN_TYPE
				), array("mc2.item_name as admin_type_name"));
		
		$select->joinLeft(array("mc3" => Core_Util_TableNames::MST_CLASS),
				$this->_db->quoteInto(
					"mus.trans_type = mc3.item_cd and mc3.delete_flg = '0' and mc3.item_type LIKE ?", Core_Util_Const::ITEM_TYPE_TRANS_TYPE
				), array("mc3.item_name as trans_type"));
		
		$select->joinLeft(array("mc4" => Core_Util_TableNames::MST_CLASS),
				$this->_db->quoteInto(
					"mu.area_code = mc4.item_cd and mc4.delete_flg = '0' and mc4.item_type LIKE ?", Core_Util_Const::AREA_CODE_CLASS
				), array("mc4.item_name as area_code_class"));
		
				
		if ($user_name != null){
			$select->where("mu.user_name like ?", "%$user_name%" );
		}
		if ($area_code != null){
			$select->where("mu.area_code = ?", $area_code );
		}
		if ($tel_no != null){
			$select->where("mu.tel_no like ?", "%$tel_no%" );
		}
		if ($sales_id != null){
			$select->where("mu.sales_id = ?", $sales_id );
		}

		$select->order("mu.login_username");
		$select->order("mus.shipping_seq");
		$rows = $this->fetchAll ( $select )->toArray();
		return $rows;
	}
	
	
	public function queryCountAllUser($user_name=null, $area_code=null, $tel_no=null, $sales_id=null) {
		$select = $this->select()->setIntegrityCheck ( false )->distinct()
		->from(array("mu"=>$this->_name), array("count"=>"count(*)"))
		->where("mu.delete_flg = 0");

// 		$select->joinLeft(array("mus" => Core_Util_TableNames::MST_USER_SHIPPING),
// 				"mus.user_id = mu.user_id",null);

		if ($user_name != null){
			$select->where("mu.user_name like ?", "%$user_name%" );
		}
		if ($area_code != null){
			$select->where("mu.area_code = ?", $area_code );
		}
		if ($tel_no != null){
			$select->where("mu.tel_no like ?", "%$tel_no%" );
		}
		if ($sales_id != null){
			$select->where("mu.sales_id = ?", $sales_id );
		}
		$rows = $this->fetchRow( $select );
		return $rows;
	}

	public function queryUserDetail($user_id) {
		$select = $this->select()->setIntegrityCheck ( false )->distinct()
		->from(array("mu"=>$this->_name), array("*", "tel_no" => "mu.tel_no", "fax_no" => "mu.fax_no"))
		->where("mu.delete_flg = 0 and mu.user_id = ?", $user_id);

		/*$select->joinLeft(array("mus" => Core_Util_TableNames::MST_USER_SHIPPING),
				"mus.user_id = mu.user_id",
				array("tel_no"=>"mus.tel_no"));*/

		$select->joinLeft(array("mc1" => Core_Util_TableNames::MST_CLASS),
				$this->_db->quoteInto(
					"mu.user_class = mc1.item_cd and mc1.item_type LIKE ?", Core_Util_Const::USER_TYPE
				), array("user_type_name"=>"mc1.item_name"));
		$select->joinLeft(array("mc2" => Core_Util_TableNames::MST_CLASS),
				$this->_db->quoteInto(
						"mu.admin_class = mc2.item_cd and mc2.item_type LIKE ?", Core_Util_Const::ADMIN_TYPE
				), array("admin_type_name"=>"mc2.item_name"));

		$select->joinLeft(array("mu1" => Core_Util_TableNames::MST_USER),
				"mu1.user_id = mu.sales_id", array("sales_name"=>"mu1.user_name"));
		$rows = $this->fetchRow( $select );
		return $rows;
	}

	public function deleteUserById($user_id) {
		$data = array(
				"delete_flg" => 1,
				"token_cookie"=>null,
				"token_cookie_admin"=>null
		);
		
		$this->update($data, array("user_id = ?" => $user_id));
	}

	public function queryNextUserId() {
		$select = $this->select()->setIntegrityCheck ( false )->distinct()
		->from(array("mu"=>$this->_name), array("user_id"=>"max(user_id)"));
		$rows = $this->fetchRow( $select );
		$user_id_next = Core_Util_Helper::getDataRow($rows, 'user_id');
		if ($user_id_next==null) $user_id_next=0;
		$user_id_next++;
		return $user_id_next;
	}

	public function getSaleUser(){

		$select = $this->select()->setIntegrityCheck (false)->from(array("t"=>$this->_name),array("t.*"))
		->where("t.delete_flg = ? and t.user_id in ((
                select sales_id from ".Core_Util_TableNames::MST_USER."
                where delete_flg = 0 and sales_id is not null
                GROUP BY sales_id))", '0')
	                ->order("t.sales_id");

		$rows = $this->fetchAll($select);

		return $rows;
	}


	/**
	 *
	 * @param int $userId
	 * @return Core_Models_MstUser
	 */
	public function getUserById($userId) {
		$where = array ();
		$where ['user_id  = ?'] = $userId;

		//add start nguyenpth 2014/04/23
		$where ['delete_flg  = ?']	= Core_Util_Const::DELETE_FLG_0;
		//add end nguyenpth 2014/04/23

		$user = $this->get ( $where );
		return $user;
	}

	public function updateUserByUsername($user, $username) {
		$where ['login_username = ?'] = $username;
		// ADD 20140424 Hieunm start
		$where ['delete_flg = ?']	  = Core_Util_Const::DELETE_FLG_0;
		// ADD 20140424 Hieunm end

		//add start nguyenpth 2014/04/23
		$chageDate = new Zend_Db_Expr ( 'NOW()' );
		//$where ['update_ymd  = ?']	= Core_Util_Const::DELETE_FLG_0;

		//add end nguyenpth 2014/04/23


		$result = false;
		if ($user !== null && $user !== false) {

			//add start nguyenpth 2014/04/23
			$user->setUpdateYmd($chageDate);
			//add end nguyenpth 2014/04/23

			$result = $this->updateRecord($user, $where);
		}
		return $result;
	}
	
	public function checkExistLoginUser($loginUser) {
		$select = $this->select()->setIntegrityCheck ( false )
		->from(array("mu"=>$this->_name), array("count"=>"count(*)"));
		
		$select->where('trim(mu.login_username) collate utf8_unicode_ci = ?', $loginUser);
		$select->where('mu.delete_flg = ?', Core_Util_Const::DELETE_FLG_0);
		
		$rows = $this->fetchRow( $select );
		$count = Core_Util_Helper::getDataRow($rows, 'count');
		return $count;
		
	}

}
