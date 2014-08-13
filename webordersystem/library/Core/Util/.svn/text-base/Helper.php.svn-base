<?php
class Core_Util_Helper {
	public static function logOutAdmin() {
		$loginSession = new Zend_Session_Namespace(
				Core_Util_Const::SESSION_NAMESPACE_LOGIN_ADMIN);
		if (isset($loginSession->loginInfoAdmin)) {
			unset($loginSession->loginInfoAdmin);
		}
		//unset sort product
		$sortClassSession = new Zend_Session_Namespace('Display');
		if(isset($sortClassSession->sort)){
			unset($sortClassSession->sort);
			
		}	
		$l = new Zend_Session_Namespace(
				Core_Util_Const::SESSION_NAMESPACE_LOGIN_ADMIN);
		if (isset($loginSession->loginInfoAdmin)) {
			unset($loginSession->loginInfoAdmin);
		}
	}

	public static function logOutUser() {
		$loginSession = new Zend_Session_Namespace ( Core_Util_Const::SESSION_NAMESPACE_LOGIN );
		if (isset ( $loginSession->loginInfo )) {
			unset ( $loginSession->loginInfo );
		}
	}
	
	public static function getAdminSession() {
		$loginSession = new Zend_Session_Namespace(
				Core_Util_Const::SESSION_NAMESPACE_LOGIN_ADMIN);
		return $loginSession;
	}
	
	public static function getUserSession() {
		$loginSession = new Zend_Session_Namespace(
				Core_Util_Const::SESSION_NAMESPACE_LOGIN);
		return $loginSession;
	}
	
	public static function getAdminClass() {
		$adminLogin = self::getLoginAdmin();
		if ($adminLogin == null) {
			return null;
		} else {
			return $adminLogin->getAdminFlg();
		}
	}
	
	public static function confirmAdminClass($confirmAdminClass) {
		$adminClass = self::getAdminClass();
		if ($adminClass === null) {
			return false;
		} else {
			return $adminClass == $confirmAdminClass;
		}
	}
	
	public static function isReferenceAdmin() {
		return self::confirmAdminClass(Core_Util_Const::ADMIN_TYPE_REFERENCE_ONLY);
	}
	
	public static function isMasterAdmin() {
		return self::confirmAdminClass(Core_Util_Const::ADMIN_TYPE_MASTER_BE_CHANGED);
	}
	
	public static function isSystemAdmin() {
		return self::confirmAdminClass(Core_Util_Const::ADMIN_TYPE_SYSTEM_ADMINISTRATOR);
	}
	
	public static function isReferAndMasterAdmin(){
		return self::isReferenceAdmin() || self::isMasterAdmin();
	}

	public static function isLogin(){
		//return self::getLoginUser() !== null;
		$loginSession = self::getLoginUser();

		if ($loginSession === null)
			return false;

		$staffSer = new Core_Service_UserService();
		$userLogin = $staffSer->authorizeSession($loginSession);

		if ($userLogin){
			return true;
		} else {
			Zend_Session::destroy(true);
			setcookie(Core_Util_Const::TOCKEN_COOKIE,null,time()-Core_Util_Helper::getTimeCookie());
			return false;
		}
	}


	public static function isLoginAdmin(){
		$loginSession = self::getLoginAdmin();
		if ($loginSession === null)
			return false;
		if ($loginSession->getAdminFlg() === Core_Util_Const::ADMIN_TYPE_NO){
			return false;
		}
		$staffSer = new Core_Service_UserService();
		$userLogin = $staffSer->loginAdminSession($loginSession);

		if ($userLogin){
			return true;
		} else {
			Zend_Session::destroy(true);
			setcookie(Core_Util_Const::TOCKEN_COOKIE_ADMIN,null,time()-Core_Util_Helper::getTimeCookie());
			return false;
		}
	}

	/**
	 * get login user
	 * @return Core_Models_MstUser
	 */
	public static function getLoginUser() {
		$loginSession = new Zend_Session_Namespace(
				Core_Util_Const::SESSION_NAMESPACE_LOGIN);
		if (isset($loginSession->loginInfo)) {
			return $loginSession->loginInfo;
		} else {
			return null;
		}
	}

	/**
	 * get login admin
	 * @return Core_Models_MstUser
	 */
	public static function getLoginAdmin() {
		$loginSession = new Zend_Session_Namespace(
				Core_Util_Const::SESSION_NAMESPACE_LOGIN_ADMIN);

		if (isset($loginSession->loginInfoAdmin)) {
			return $loginSession->loginInfoAdmin;
		} else {
			return null;
		}
	}

	public static function getIdAdminLogin() {
		$adminLogin = self::getLoginAdmin();
		if ($adminLogin != null) {
			return $adminLogin->getUserId();
		}

		return null;

	}

	/**
	 * get login userid
	 * @return Core_Models_MstUser
	 */
	/* public static function getLoginUserId() {
		$loginSession = new Zend_Session_Namespace(
				Core_Util_Const::SESSION_NAMESPACE_LOGIN);
		if (isset($loginSession->userId)) {
			return $loginSession->userId;
		} else {
			return null;
		}
	} */

	/**
	 * get id of login user
	 * @return NULL
	 */
	public static function getIdUserLogin() {
		$userLogin = self::getLoginUser();
		if ($userLogin !== null) {
			return $userLogin->getUserId();
		} else {
			return null;
		}
	}

	public static function setLoginUserOrderCart($arrOrderCart) {
		$loginSession = new Zend_Session_Namespace(
				Core_Util_Const::SESSION_NAMESPACE_LOGIN);
		$user = $loginSession->loginInfo;
		$user->setArrOrderCart($arrOrderCart);
		$loginSession->loginInfo = $user;
	}

	public static function setLoginUserQuotationCart($arrQuotationCart) {
		$loginSession = new Zend_Session_Namespace(
				Core_Util_Const::SESSION_NAMESPACE_LOGIN);
		$user = $loginSession->loginInfo;
		$user->setArrQuotationCart($arrQuotationCart);
		$loginSession->loginInfo = $user;
	}

	/**
	 * check is null or empty string
	 * @param string $data
	 * @return boolean
	 */
	public static function isEmpty($data) {
		return $data === null || trim($data) === "";
	}

	public static function isNotEmpty($data) {
		return !self::isEmpty($data);
	}

	/**
	 * encrypt password to save database
	 * @param string $password
	 * @return string|NULL
	 */
	public static function encryptPassword($password) {
		if (!self::isEmpty($password)) {
			return md5($password);
		} else {
			return null;
		}
	}


	public static function getOffset($page, $count = null) {
		if ($count === null) {
			$count = Core_Util_Const::NUMBER_ITEMS_PAGE;
		}

		return ($page - 1) * $count;
	}

	public static function nullToZero($number) {
		if ($number === null) {
			return 0;
		} else {
			return $number;
		}
	}

	public static function nullToBlank($str) {
		if ($str === null) {
			return "";
		} else {
			return $str;
		}
	}

	public static function nullToEmptyArray($arr) {
		if ($arr === null) {
			return array();
		} else {
			return $arr;
		}
	}

	public static function getDataRow($data, $index, $default = null) {
		$ret = $default;
		if (isset($data[$index]) == TRUE) {
			$ret = $data[$index];
		}
		return $ret;
	}

    public static function getErrorMessage($form, $nameFormElement,
			$prefix = "", $separator = "") {
		if ($form == null) {
			return "";
		}
		$arrMessErrorForm = $form->getMessages();
		$strErrorMess = "";

		if (isset($arrMessErrorForm[$nameFormElement])) {
			$index = 0;
			$lengthErrors = count($arrMessErrorForm[$nameFormElement]);

			foreach ($arrMessErrorForm[$nameFormElement] as $key => $val) {
				$strErrorMess .= $val;

				if ($index != ($lengthErrors - 1)) {
					$strErrorMess .= $separator;
				}

				$index++;
			}
		}
		$$strErrorMess = trim($strErrorMess);
		$lenght = strlen($strErrorMess);

		if ($lenght > 0) {
			$strErrorMess = $strErrorMess . $prefix;
		}

		return $strErrorMess;
	}

    public function checkDate($valueName, $value,$notNull,$title){
		if($title=='') $title = $valueName;
		$value = trim($value);
		$value = preg_replace("/\s+/","/\s/",$value);	// 複数空白文字の単一化
		$value = str_replace("-","/",$value);
        $msg = "";
		if($value!='' or $notNull==true){
			if($value==''){
				$msg = Core_Util_Messages::getMessage(Core_Util_Messages::W001, array($title));
				return $msg;
			}
			list($date,$time) = self::core_explode(" ",$value,2);
			list($yy,$mm,$dd) = self::core_explode("/",$date,3);
			$err_flg = 0;
			if((!is_numeric($yy) || $yy<1900) && $err_flg==0) $err_flg = 1;
			if((!is_numeric($mm) || $mm<1	 || $mm>12) && $err_flg==0) $err_flg = 2;
			if((!is_numeric($dd) || $dd<1	 || $dd>31) && $err_flg==0) $err_flg = 3;

			if ($err_flg == 0) {
				if($yy <1972){
					$dumy_yy = 1972 + ($yy % 4);
				}
				else{
					$dumy_yy = $yy;
				}
				$timestamp = mktime(0,0,0,$mm,$dd,$dumy_yy);
				$datestr = $yy . date("/m/d", $timestamp);
				list($yy2,$mm2,$dd2)=self::core_explode("/",$datestr,3);
				if($yy != $yy2 || $mm != $mm2 || $dd != $dd2){
					$msg = Core_Util_Messages::getMessage(Core_Util_Messages::W009, array($title));
				    return $msg;
				}
			} else {
				$msg = Core_Util_Messages::getMessage(Core_Util_Messages::W009, array($title));
				    return $msg;
			}
		}
		return $msg;
	}

    public static function checkString($valueName,$value,$min,$max,$notNull,$title){
		if($title=='') $title = $valueName;
		$length = self::eucValue($value);
		$msg = "";
		if($value!='' || $notNull==true){
			if($value==''){
				$msg = Core_Util_Messages::getMessage(Core_Util_Messages::W001, array($title));
				return $msg;
			}
			if(!is_null($min) and !is_null($max)){
				if($min==$max){
					if($min!=$length){
						$msg = ''.$title.'を半角'.$min.'文字で入力して下さい。';
						return $msg;
					}
				}
				elseif($min>$length || $max<$length){
					$msg = ''.$title.'を半角'.$min.'文字以上'.$max.'文字以下で入力して下さい。';
					return $msg;
				}
			}
			elseif(is_null($max)){
				if($min>$length){
					$msg = ''.$title.'を半角'.$min.'文字以上で入力して下さい。';
					return $msg;
				}
			}
			elseif(is_null($min)){
				if($max<$length){
					$msg = ''.$title.'を半角'.$max.'文字以下で入力して下さい。';
					return $msg;
				}
			}
		}
		return $msg;
	}

    public static function checkNumberic($valueName,$value,$min,$max,$notNull,$title){
		if($title=='') $title = $valueName;
		$length = strlen($value);
        $msg = "";

		if($value!='' or $notNull==true){
			if($value===''){
				$msg = Core_Util_Messages::getMessage(Core_Util_Messages::W001, array($title));
				return $msg;
			}
			if(!is_null($min) and !is_null($max)){
				if($min==$max){
					if($min!=$length){
						$msg = $title.'を半角数値'.$min.'で入力して下さい。';
						return $msg;
					}
				}
				elseif($min>$length or $max<$length){
					$msg = $title.'を'.$min.'～'.$max.'で入力して下さい。';
					return $msg;
				}
			}
			elseif(is_null($max)){
				if($min>$length){
				    $msg = $title.'を'.$min.'以上で入力して下さい。';
					return $msg;
				}
			}
			else{
				if($max<$length){
				    $msg = $title.'を'.$max.'以下で入力して下さい。';
					return $msg;
				}
			}
			if (!is_numeric($value)){
			    $msg = $title.'を半角数値で入力して下さい。';
				return $msg;
			}else if(preg_match("/^0+[0-9]+$/", $value)){
			    $msg = $title.'を半角数値で入力して下さい。';
				return $msg;
			}
		}
		return $msg;
	}

    function core_explode($separator,$string,$limit=null){
    	if(is_null($limit)) return explode($separator,$string);
    	$ret = explode($separator,$string,$limit);
    	$fill = $limit-count($ret);
    	for($i=0;$i<$fill;$i++) $ret[] = null;
    	return $ret;
    }

    function eucValue($value){
		if("UTF-8"!="EUC-JP"){
			# 文字コード変換
			return strlen(mb_convert_encoding($value,"EUC-JP","UTF-8"));
		}
		return strlen($value);
	}

	public static function getArrFromString($str, $sep = ",") {
		$arr = array();

		if (self::isNotEmpty($str)) {
			$arrTemp = explode($sep, $str);
			foreach ($arrTemp as $key => $value) {
				$value = trim($value);
				if (self::isNotEmpty($value)){
					$arr[] = $value;
				}
			}
		}

		return $arr;
	}

	public static function getPointRate() {
		$classServ = new Core_Service_MstClassService();
		$arr = $classServ->getMstClassByItemType(Core_Util_Const::POINT_RATE_CLASS);

		/* @var $curItem Core_Models_MstClass */
		$curItem = null;
		/* @var $item Core_Models_MstClass */
		$curDate = date("Y-m-d");

		foreach ($arr as $key => $item) {
			$itemDate = $item->getNote1();

			if (strcmp($itemDate, $curDate) <= 0) {

				if ($curItem === null) {
					$curItem = $item;
				} else {
					if (strcmp($curItem->getNote1(), $item->getNote1()) < 0) {
						$curItem = $item;
					}
				}
			}
		}
		$pointRate = Core_Util_Const::POINT_RATE_DEFAULT;
		if ($curItem !== null) {
			$pointRate = $curItem->getNote2();
		}

 		return $pointRate;
	}

	public static function nullEmptyToValue($data, $defaultValue) {
		if (self::isEmpty($data)) {
			return $defaultValue;
		} else {
			return $data;
		}
	}

	public static function getCurrentMysqlTime() {
		$curDate = date( 'Y-m-d h:i:s');
		return $curDate;
	}

	public static function exportCSV($filename, $result, $column_header=null) {
		header( "Content-Type: text/csv;charset=utf-8" );
		header( "Content-Disposition: attachment;filename=\"$filename\"" );
		header("Pragma: no-cache");
		header("Expires: 0");

		$fp= fopen('php://output', 'w');

		if ($column_header != null && is_array($column_header)){
			fputcsv($fp, $column_header);
		}
		if ($result != null && is_array($result)){
			foreach ($result as $row) {
				fputcsv($fp, $row);
			}
		}

		fclose($fp);
		exit();
	}

	public static function importCSV($filename) {
		$result = array();
		$row = 1;
		if (($handle = fopen($filename, "r")) !== FALSE) {
			while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
				$result[]=$data;
			}
			fclose($handle);
		}
		return $result;
	}

	public static function getImageProductFolderTemp() {
		$clsServ = new Core_Service_MstClassService();
		$pathImageTemp = $clsServ->getItemTypeNote1(Core_Util_Const::UPLOAD_PRODUCT_IMAGE_ITEM_TYPE, Core_Util_Const::UPLOAD_PRODUCT_IMAGE_PATH_TEMP_ITEM_CD);

		$pathImage = Core_Util_Const::UPLOAD_PRODUCT_IMAGE_PATH_TEMP;

		if (Core_Util_Helper::isNotEmpty($pathImageTemp)) {
			$pathImage = $pathImageTemp;
		}

		return $pathImage;
	}

	public static function getImageProductFolder() {
		$clsServ = new Core_Service_MstClassService();
		$pathImageFromClass = $clsServ->getItemTypeNote1(Core_Util_Const::UPLOAD_PRODUCT_IMAGE_ITEM_TYPE, Core_Util_Const::UPLOAD_PRODUCT_IMAGE_PATH_ITEM_CD);

		$pathImage = Core_Util_Const::UPLOAD_PRODUCT_IMAGE_PATH;

		if (Core_Util_Helper::isNotEmpty($pathImageFromClass)) {
			$pathImage = $pathImageFromClass;
		}

		return $pathImage;
	}

	// ADD 20140512 Hieunm start
	public static function getImageProductFolderThumb() {
		$clsServ = new Core_Service_MstClassService();
		$pathImageFromClass = $clsServ->getItemTypeNote1(Core_Util_Const::UPLOAD_PRODUCT_IMAGE_ITEM_TYPE, Core_Util_Const::UPLOAD_PRODUCT_IMAGE_PATH_THUMB_TEMP_ITEM_CD);

		$pathImage = Core_Util_Const::UPLOAD_PRODUCT_IMAGE_THUMB_PATH;

		if (Core_Util_Helper::isNotEmpty($pathImageFromClass)) {
			$pathImage = $pathImageFromClass;
		}

		return $pathImage;
	}
	// ADD 20140512 Hieunm end

	public static function getTimeCookie(){
		return 60*60*24*Core_Util_Const::TOCKEN_COOKIE_DATE;
	}

	public static function createMetadataForUpdateRow($arrData) {
		if ($arrData !== null && is_array($arrData)) {
			//$idUserLogin = self::getIdUserLogin();
			$idUserLogin = self::getIdAdminLogin();
			$arrData['update_ymd'] = new Zend_Db_Expr(" NOW() ");
			$arrData['update_user_id'] = $idUserLogin;
		}
		return $arrData;
	}

	// ADD 20140512 Hieunm start
	function makeThumbnails($upDir, $img, $desDir, $width = null, $height = null) {
	    $thumbnail_width = $width !== null ? $width : Core_Util_Const::THUMBNAIL_WIDTH_DEFAULT;
	    $thumbnail_height = $height !== null ? $height : Core_Util_Const::THUMBNAIL_HEIGHT_DEFAULT;
	    $thumb_beforeword = Core_Util_Const::THUMBNAIL_PRE_FIX_NAME_DEFAULT;

	    $arr_image_details = getimagesize("$upDir" . "$img"); // pass id to thumb name
	    $original_width = $arr_image_details[0];
	    $original_height = $arr_image_details[1];

	    if ($original_width > $original_height) {
	        $new_width = $thumbnail_width;
	        $new_height = intval($original_height * $new_width / $original_width);
	    } else {
	        $new_height = $thumbnail_height;
	        $new_width = intval($original_width * $new_height / $original_height);
	    }

	    $dest_x = intval(($thumbnail_width - $new_width) / 2);
	    $dest_y = intval(($thumbnail_height - $new_height) / 2);

	    if ($arr_image_details[2] == 1) {
	        $imgt = "ImageGIF";
	        $imgcreatefrom = "ImageCreateFromGIF";
	    }

	    if ($arr_image_details[2] == 2) {
	        $imgt = "ImageJPEG";
	        $imgcreatefrom = "ImageCreateFromJPEG";
	    }

	    if ($arr_image_details[2] == 3) {
	        $imgt = "ImagePNG";
	        $imgcreatefrom = "ImageCreateFromPNG";
	    }

	    if ($imgt) {
	        $old_image = $imgcreatefrom("$upDir" . "$img");
	        $new_image = imagecreatetruecolor($thumbnail_width, $thumbnail_height);
	        //$backgroundColor = imagecolortransparent($new_image);
	        $backgroundColor = imagecolorallocate($new_image, 255, 255, 255);
	        imagefill($new_image, 0, 0, $backgroundColor);
	        //imagecopyresized($new_image, $old_image, $dest_x, $dest_y, 0, 0, $new_width, $new_height, $original_width, $original_height);
	        imagecopyresampled($new_image, $old_image, $dest_x, $dest_y, 0, 0, $new_width, $new_height, $original_width, $original_height);
	        imagejpeg($new_image, "$desDir" . "$thumb_beforeword" . "$img", 100);
	        //$imgt($new_image, "$desDir" . "$thumb_beforeword" . "$img");
	        return;
	    }
	}
	// ADD 20140512 Hieunm end
	
	public static function getShippingMethodName($id) {
		if (Core_Util_Helper::isEmpty($id)) {
			return "";
		}
		$db = new Core_Db_MstClassDb();
		$item = $db->getMstClassByItemTypeAndItemCdDispl(Core_Util_Const::ITEM_TYPE_TRANS_TYPE, $id);
		if ($item != null) { 
			return $item->getItemName();
		} else {
			return "";
		}
	}
	
	public static function validateDate($date, $format = 'Ymd')
	{
	    $d = DateTime::createFromFormat($format, $date);
	    return $d && $d->format($format) == $date;
	} 
	
	public static function getArrCdNameFromString($str) {
		$arr = array();
		if (strpos($str,':') !== false) {
			$arr = explode(":", $str);
		} else if(strpos($str,'：') !== false){
			$arr = explode("：", $str);
		}
		
		if (count($arr) != 2 || Core_Util_Helper::isEmpty($arr[0]) || Core_Util_Helper::isEmpty($arr[1])) {
			return false;
		} else {
			return $arr;
		}
	}
	
	public static function isInArray($arr, $obj) {
		foreach ($arr as $key => $value) {
			if ($value == $obj) {
				return true;
			}
		}
		
		return false;
		
	}
	
	/**
	 * from yyyyMMdd to yyyy-MM-dd
	 * @param string $date with format yyyyMMdd
	 */
	public static function formatToMySQLDate($date) {
		if (strlen($date) != 8) {
			return false;
		} else {
			$year = substr($date, 0, 4);
			$month = substr($date, 4,2);
			$day = substr($date, 6,2);
			return $year . "-" . $month . "-" . $day;
		}
	}

}