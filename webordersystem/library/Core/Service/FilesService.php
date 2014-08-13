<?php
/**
 * 
 * @author Nguyen
 *
 */
class Core_Service_FilesService extends Core_Service_Abstract {
	
	public function getAllImages($idLogin = "") {
		$path = $this->getDirImage($idLogin);
		$arr = array();
		if ($handle = opendir($path)) {
			while (false !== ($entry = readdir($handle))) {
				if ($entry != "." && $entry != ".." && $entry != ".svn") {
					//echo "$entry\n";
					$arr[] = $entry;
				}
			}
			closedir($handle);
		}
		return $arr;
	}
	
	public function getDirImage ($idLoginUser = ""){
		$path = __DIR__.'/../../../public/images/'.Core_Util_Const::IMAGE_EDITOR_DIR;
		if ($idLoginUser !== "") {
			$path = $path . "/" . $idLoginUser;
		}
		
		if (!file_exists($path)) {
			mkdir ( $path, 0777 );
		}
		return $path;
	}

}