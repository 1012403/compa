<?php
class Admin_CategoryController extends Core_Controller_AdminAbstract{
	private $screenName = "商品カテゴリー";
	public function init() {
		parent::init();
		$this->view->headLink()->prependStylesheet(Zend_Registry::get('url_base').'/css/admin/style.css');
		$this->view->headLink()->prependStylesheet(Zend_Registry::get('url_base').'/css/style.css');
		$this->view->headLink()->prependStylesheet(Zend_Registry::get('url_base').'/css/user/adminlayout.css');

		//$this->view->headScript()->appendFile(Zend_Registry::get('url_base').'/js/admin/mail.js');
		$this->view->headScript()->appendFile(Zend_Registry::get('url_base').'/js/jquery.form.3.5.js');
		parent::createMenuOther();
	}
	/**
	 * indexAction
	 */
	public function indexAction(){
		parent::visitByButton($this->screenName,"");
		$sevice=new Core_Service_MstCategoryService();
		$formData = $this->_getAllParams();
		$catPerent=$sevice->getCategoryParent();
		$this->view->catPerent=$catPerent;

	}

	/**
	 * category child
	 */
	public function categoryAction(){
		parent::visitByButton($this->screenName,"追加");
		$sevice=new Core_Service_MstCategoryService();
		$formData = $this->_getAllParams();
		$catByParId=$sevice->getByParentId($formData['id']);
		$this->view->catByParId=$catByParId;
		$this->_helper->layout->disableLayout();
	}
	/**
	 * add category child
	 */
	public function addchildAction(){
		parent::visitByButton($this->screenName,"追加");
		$formData = $this->_getAllParams();

		$sevice=new Core_Service_MstCategoryService();
		$data = array();
		$data['category_name']=$formData ['category_name'];
		$data['parent_id']=$formData ['parent_id'];
		if(Core_Util_Helper::isNotEmpty($data['category_name'])){
			$result=$sevice->insertCategory($data);
			if ($result == 0){
				echo "false";
				$this->_helper->viewRenderer->setNoRender();
			} else {
				$dis=$sevice->getByCategoryId($result);
				$dislay=$dis->getDisplayOrder();
				$value=array();
				$value['category_id']=$result;
				$value['display_order']=$dislay;
				$value['category_name']=$formData['category_name'];
				$this->view->value = $value;
			}
		}else {
			//$err=$error=Core_Util_Messages::getMessage(Core_Util_Messages::N006);
			//echo $err;
		}

		$this->_helper->layout->disableLayout();
	}
	/**
	 * add category parent
	 */
	public function addparentAction(){
		parent::visitByButton($this->screenName,"追加");
		$formData = $this->_getAllParams();
		$sevice=new Core_Service_MstCategoryService();
		$data = array();
		$data['parent_id']=null;
		$data['category_name']=$formData ['category_name'];
		if(Core_Util_Helper::isNotEmpty($data['category_name'])){
			$result=$sevice->insertCategory($data);
			if ($result == 0){
				echo "false";
				$this->_helper->viewRenderer->setNoRender();
			} else {
				$dis=$sevice->getByCategoryId($result);
				$dislay=$dis->getDisplayOrder();

				$value=array();
				$value['category_id']=$result;
				$value['display_order']=$dislay;
				$value['category_name']=$formData['category_name'];
				echo "@@@".$value['category_id'];
				$this->view->valueparent = $value;
			}
		}else {
			//$err=$error=Core_Util_Messages::getMessage(Core_Util_Messages::N006);
			//echo $err;
		}
		$this->_helper->layout->disableLayout();
	}
	/**
	 * edit category parent
	 */
	public function editparentAction(){
		parent::visitByButton($this->screenName,"更新");
		$formData = $this->_getAllParams();
		$sevice=new Core_Service_MstCategoryService();
		$data = array();
		$id=$formData ['category_id'];
		$data['category_name']=$formData ['category_name'];
		if(Core_Util_Helper::isNotEmpty($data['category_name'])){
			$parent=$sevice->updateCategory($id, $data);
			if($parent){
				echo "true";
				$this->_helper->layout->disableLayout();
				$this->_helper->viewRenderer->setNoRender(TRUE);
			}else {
				echo "false";
			}
		}else{
			//error
			//$err=$error=Core_Util_Messages::getMessage(Core_Util_Messages::N006);
			//echo $err;
		}
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender(TRUE);
	}

	/**
	 * edit cegory child
	 */
	public function editchildAction(){
		parent::visitByButton($this->screenName,"更新");
		$formData = $this->_getAllParams();
		$sevice=new Core_Service_MstCategoryService();
		$data = array();
		$id=$formData ['category_id'];
		$data['category_name']=$formData ['category_name'];
		if(Core_Util_Helper::isNotEmpty($data['category_name'])){
			$parent=$sevice->updateCategory($id, $data);
			if($parent){
				echo "true";
				$this->_helper->layout->disableLayout();
				$this->_helper->viewRenderer->setNoRender(TRUE);
			}else {
				echo "false";
			}
		}else{
			//loi
			//$err=$error=Core_Util_Messages::getMessage(Core_Util_Messages::N006);
			//echo $err;
		}
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender(TRUE);
	}

	/**
	 * delete category paren
	 * @param unknown $id
	 */
	public function deleteparentAction(){
		parent::visitByButton($this->screenName,"削除");
		$formData = $this->_getAllParams();
		$sevice=new Core_Service_MstCategoryService();
		$id=$formData ['category_id'];

		$arrcat=$sevice->getCategoryParent();
		//delete cate by id
		$dis=$sevice->getByCategoryId($id);
		$dislay=$dis->getDisplayOrder();
		//get id child by idparent
		$catPar=$sevice->getCategoryByIdParent($id);
		$isssetIs="";
		$nextProc = true;
		foreach ($catPar as $arrcatChild){
			$idChildOfPar=$arrcatChild['category_id'];
			$db = new Core_Db_MstCategoryDb();
			$isssetIs=$db->isCatProdJoin($idChildOfPar);
			if ($isssetIs){
				$nextProc = false;
				break;
			}
		}
		if($isssetIs!="false" && $nextProc){
			//delete child
			$delChildbyChar=$sevice->deleteChildOfPar($id);
			//delete parent
			$isdelete=$sevice->deleteCategory($id);
			//update dislay
			$update=$sevice->updateDislay($dislay,null);

			if($delChildbyChar && $isdelete && $update){
				echo "true";
			}
			else{
				echo "false";
			}
		} else{
			echo "false";
		}

		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender(TRUE);
	}
	/**
	 * delete category child
	 */
	public function deletechidAction(){
		parent::visitByButton($this->screenName,"削除");
		$formData = $this->_getAllParams();
		$sevice=new Core_Service_MstCategoryService();
		$id=$formData ['category_id'];

		$dis=$sevice->getByCategoryId($id);
		$idpar=$dis->getParentId();
		$dislay=$dis->getDisplayOrder();

		//$updatedis
		$isdelete=$sevice->deleteCategory($id);
		//update dislay
		$update=$sevice->updateDislay($dislay,$idpar);

		if($isdelete && $update){
			echo "true";
			$this->_helper->layout->disableLayout();
			$this->_helper->viewRenderer->setNoRender(TRUE);
		}else{
			echo "false";
			$this->_helper->layout->disableLayout();
			$this->_helper->viewRenderer->setNoRender(TRUE);
		}
	}

	/**
	 * moveup category
	 */
	public function moveupAction(){
		parent::visitByButton($this->screenName,"↑移動");
		$formData = $this->_getAllParams();
		$sevice=new Core_Service_MstCategoryService();
		$id=$formData ['category_id'];
		$id2=$formData ['category_id_2'];

		$cat=$sevice->getByCategoryId($id);
		$idparent=$cat->getParentId();
		$disOld=$cat->getDisplayOrder();
		$mindis=$sevice->getMinDisplayOrder($idparent);
		if ((int)$disOld==(int)$mindis){
			echo "false";
		}
		else {
			//update dislay 1
			$disNew=(int)$disOld-1;
			$data = array();
			$data['display_order']=$disNew;
			$dislayorder=$sevice->updateCategory($id, $data);
			//update disly 2
			$cat2=$sevice->getByCategoryId($id2);
			$cats2=$cat2->getDisplayOrder();

			$dislaynew2=(int)$cats2+1;
			$data2 = array();
			$data2['display_order']=$dislaynew2;
			$dislayorder2=$sevice->updateCategory($id2, $data2);
			if($dislayorder && $dislayorder2){
				echo "true";
				$this->_helper->layout->disableLayout();
				$this->_helper->viewRenderer->setNoRender(TRUE);
			}else {
				echo "false";
			}
		}
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender(TRUE);

	}
	/**
	 * movedow category
	 */
	public function movedowAction(){
		parent::visitByButton($this->screenName,"↓移動");
		$formData = $this->_getAllParams();
		$sevice=new Core_Service_MstCategoryService();

		$id=$formData ['category_id'];
		$id2=$formData ['category_id_2'];

		$cat=$sevice->getByCategoryId($id);
		$idParent=$cat->getParentId();

		$disOld=$cat->getDisplayOrder();
		$disMax=$sevice->getMaxDisplayOrder($idParent);
		if((int)$disOld==(int)$disMax){
			echo "false";
		}
		else {
			$dislaynew=(int)$disOld+1;
			$data = array();
			$data['display_order']=$dislaynew;
			$dislayorder=$sevice->updateCategory($id, $data);

			$cat2=$sevice->getByCategoryId($id2);
			$cats2=$cat2->getDisplayOrder();
			$dislaynew2=(int)$cats2-1;
			$data2 = array();
			$data2['display_order']=$dislaynew2;
			$dislayorder2=$sevice->updateCategory($id2, $data2);
			//
			if($dislayorder && $dislayorder2){
				echo "true";
				$this->_helper->layout->disableLayout();
				$this->_helper->viewRenderer->setNoRender(TRUE);
			}else {
				echo "false";
			}
		}
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender(TRUE);

	}
	/**
	 * exportAction
	 */
	public function exportAction(){
		parent::visitByButton($this->screenName,"ＣＳＶ出力");
		$sevice=new Core_Service_MstCategoryService();
		
		$arrCategory=$sevice->getCategoryNameParentAndChild();
		//$arrCategory=$sevice->getAllCategory();
		
		$csvData = $this->toCSVString($arrCategory);
		$filename = trim("商品カテゴリー"). ".csv";
		$result=$this->exportCSVToClient($csvData, $filename);
		
	}
	/**
	 * toCSVString
	 * @param string $arrCategory
	 * @return unknown
	 */
	private function toCSVString($arrCategory) {
		$data = $this->createCSVHeader();
		$data .= $this->createCSVBody($arrCategory);
		return $data;
	}
	/**
	 * createCSVHeader
	 * @return Ambigous <NULL, string, unknown>
	 */
	private function createCSVHeader() {
		$arrHeader = Core_Models_MstCategory::getHeaderCsv();
		$csvAgent = new Core_Models_CsvAgent();
		$header = $csvAgent->createLineString($arrHeader);
		return $header;
	}
	/**
	 * createCSVBody
	 * @param unknown $arrCategory
	 * @return Ambigous <string, NULL, unknown>
	 */
	private function createCSVBody($arrCategory) {
		$csvAgent = new Core_Models_CsvAgent();
		$csvBody = "";
		/* @var $item Core_Models_MstCategory */
		foreach ($arrCategory as $key => $item) {
			$items= new Core_Models_MstCategory($item);
			$items->setParentName($item['parent_name']);
			$csvArr = $items->toCsvData();
			$stringRow = $csvAgent->createLineString($csvArr);
			$csvBody .= $stringRow;
		}
		return $csvBody;
	}
	
	public function importcsvAction(){
		$this->disableLayout();
		$this->noRender();
		$temp = explode(".", $_FILES["fileCategory"]["name"]);
		$extension = end($temp);
		if ( isset($_FILES["fileCategory"])) {
			//if there was an error uploading the file
			if ( strtolower($extension) != "csv") {//$_FILES["fileCategory"]["error"] > 0 ||
				$error = "選択したファイルの形式が不正です。";
				echo $error;
			} else {
				$str = file_get_contents($_FILES["fileCategory"]["tmp_name"]);
				//add 20140724 locpht start;
		        if (!mb_detect_encoding($str, 'UTF-8', true)){
		        	if (mb_detect_encoding($str, 'sjis-win', true)){
		        		$str = mb_convert_encoding($str, 'utf-8', 'sjis-win');
		        	} else{
		        		$error = "文字コードが判定できません。";
		        		echo $error;
		        		return;
		        	}
		        	
		        }
		        //add 20140724 locpht end
				$arrRows = explode("\n", $str);
				unset($arrRows[0]);
				$success = $this->saveCSVRows($arrRows);
				if ($success === true) {
					echo "true";
				} else {
					$error = $success ." 行にエラーが発生しています。内容を確認してください。";
					echo $error;
				}
			}
		} else {
			echo "選択したファイルの形式が不正です。";
		}
	}
	
	private function saveCSVRows($arrRows) {
		if (is_array($arrRows)) {
			$data = array();
			// filter empty line
			foreach ($arrRows as $key => $row) {
				$row = trim($row);
				if ($row !== "") {
					$data[] = $row;
				}
			}
			$validRow="";
			$validRow = $this->validRow($data);
			/* try {
				$validRow = $this->validRow($data);
			}catch (Exception $e){
				$validRow=" ";//選択したファイルの形式が不正です。
			} */
			if (is_bool($validRow)) {
				return $this->saveRowToMstCategory($data);
			} else {
				return $validRow;
			}
			
		}
		return "arrRows is not array";
	}
	
	private function saveRowToMstCategory($arrRows){
		$serv = new Core_Service_MstCategoryService();
	
		if (is_array($arrRows)) {
			$arrMstCategory = array();
			foreach ($arrRows as $key => $row) {
				$mstCategory = Core_Models_MstCategory::createMstCategoryFromCsvRow($row);
				$arrMstCategory[] = $mstCategory;
			}
			$res = $serv->insertArrCategory($arrMstCategory);
			if (Core_Util_Helper::isEmpty($res)) {
				return true;
			} else {
				return $res;
			}
		}
		return "arrRows is not array";
	}
	
	/**
	 * validRow
	 * @param string $arrRows
	 * @return boolean|Ambigous <string, unknown>|string
	 */
	private function validRow($arrRows) {
		$error = "";
		if (is_array($arrRows)) {
			foreach ($arrRows as $key => $strRow) {
				$validARow = $this->validARow($strRow, ($key));
				if (is_string($validARow) && Core_Util_Helper::isNotEmpty($validARow)) {
					if ($error !== ""){
						$error .=", ";
					}
					$error .= $validARow ;
				}
			}
			if (Core_Util_Helper::isEmpty($error)) {
				return true;
			} else {
				return $error;
			}
		} else {
			return "データの内容が配列ではありません。";
		}
	}
	
	private function validARow($strRow, $rowIndex) {
		$rowIndex=$rowIndex+1;
		$csvAgent = new Core_Models_CsvAgent();
		$arrField = $csvAgent->convertCsvRowToArray($strRow);
		$innerError = "";
		if (count($arrField) != Core_Models_MstCategory::$TOTAL_FIELD_IMPORT) {
			//throw new Exception($rowIndex. "Csv field not match to MstCategory Obj");
			$innerError .= $rowIndex;
		} else {
			$catServ = new Core_Service_MstCategoryService();
			$cnt = count($arrField);
			for ($i = 0; $i < $cnt; $i++) {
				switch ($i) {
					case 0: // category_name Parent
						// check string length 150
						$res = Core_Util_Helper::checkString("Category Name Parent", $arrField[$i], 1, 150, true, "Category Name Parent ");
						
						if (Core_Util_Helper::isNotEmpty($res)) {
							$innerError .=  $rowIndex ;
						}
						break;
					case 1: // category_name Parent
						// check string length 150
						$res = Core_Util_Helper::checkString("Category Name Child ", $arrField[$i], null, 150, false, "Category Name Child ");
						
						if (Core_Util_Helper::isNotEmpty($res)) {
							
							$innerError .= $rowIndex ;
						}
						break;
				}
			}
		}
		return $innerError;
	
	}
}
