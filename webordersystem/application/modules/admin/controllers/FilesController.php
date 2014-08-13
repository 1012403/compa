<?php
class Admin_FilesController extends Core_Controller_AdminAbstract {

	public function init() {
		parent::init();
	}

	public function indexAction() {
		$layout = Zend_Layout::getMvcInstance();
		$layout->setLayout('layout_files');
		
		$filesServc = new Core_Service_FilesService();
		$idAdminLogin = Core_Util_Helper::getIdAdminLogin();
		$arr = $filesServc->getAllImages($idAdminLogin);
		$this->view->arrFiles = $arr;
		$callback = $_GET['CKEditorFuncNum'];
		$this->view->callback = $callback;
	}
	
	public function uploadAction(){
		$this->disableLayout();
		$this->noRender();
		$filesServc = new Core_Service_FilesService();
		$allowedExts = array (
				"gif",
				"jpeg",
				"jpg",
				"png" 
		);
		$isError = false;
		$errorMessage = "";
		
		
		$temp = explode ( ".", $_FILES ["image"] ["name"] );
		$extension = end ( $temp );
		$newName = "";
		if (($_FILES ["image"] ["size"] < 3145728) 
			&& in_array ( $extension, $allowedExts )) {
			if ($_FILES ["image"] ["error"] > 0) {
				$isError = true;
				$errorMessage = "Server not alow this file. Error code : " . $_FILES ["image"] ["error"];
			} else {
				$idUserLogin = Core_Util_Helper::getIdAdminLogin();
				$pathUpload = $filesServc->getDirImage($idUserLogin);
				if (!file_exists($pathUpload)) {
	            	mkdir ( $pathUpload, 0777 );
	            }
				$newName = $idUserLogin.date("YmdHis"). "." . $extension;
				move_uploaded_file ( $_FILES ["image"] ["tmp_name"], $pathUpload . "/" . $newName );
			}
		} else {
			$isError = true;
			$errorMessage = "invalid file type or file size (< 3MB)";
		}
		
		if ($isError) {
			echo json_encode(array('success' => false, 'error' => $errorMessage));
		} else {
			echo json_encode(array('success' => true, 'fileName' => $newName));
		}
	}
	
	public function listAction(){
		$this->disableLayout();
		$filesServc = new Core_Service_FilesService();
		$idAdminLogin = Core_Util_Helper::getIdAdminLogin();
		$arr = $filesServc->getAllImages($idAdminLogin);
		$this->view->arrFiles = $arr;
	}
	
	public function getAction(){
		$this->disableLayout();
		$allParams = $this->_getAllParams();
		$fileName = $allParams['fileName'];
		$index = $allParams['index'];
		$this->view->fileName = $fileName;
		$this->view->index = $index;
	}
	
}
