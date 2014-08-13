<?php
/**
 * Class Communityfloor_Service_BulletinBoardService Service
 * All Rights Reserved, Copyright (c) 株式会社アイ・エンター 2013
 */
class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
	protected function _initPlugin()
	{
		$plugin	= new Plugin_Authenticate();
		$front 	= Zend_Controller_Front::getInstance();
		$front->registerPlugin($plugin);
	}

	protected function _initProgram()
	{
		Core_Db_Adapter::Init($this);
	}

	//config send mail
	protected function _initDefaultEmailTransport()
	{
		$emailConfig = $this->getOption('email');

		$mailTransport = new Zend_Mail_Transport_Smtp($emailConfig['transportOptionsSmtp']['host'],
				$emailConfig['transportOptionsSmtp']);

		Zend_Mail::setDefaultTransport($mailTransport);

		Zend_Registry::set('mailTransport', $mailTransport);
	}

	// config this view for init Variable function
	protected function _initView()
	{
		$view = new Zend_View();
		$viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('ViewRenderer');
		$viewRenderer->setViewSuffix('phtml');
		$viewRenderer->setView($view);

		$this->bootstrap('layout');
		$layout = Zend_Layout::getMvcInstance();
		$layout->setViewSuffix('phtml');

		return $view;
	}

	// config url_base for file
	protected function _initVariable()
	{
		$host = @$_SERVER["HTTP_HOST"];
		$url_base = "https://$host";
		if (empty($_SERVER['HTTPS'])) {
			$url_base = "http://$host";
		}
		$this->view->config = array(
				'url_base' => $url_base
		);

		Zend_Registry::set('url_base', $this->view->config['url_base']);
		$publicDir = realpath(dirname(__FILE__) . '/../public/');
		
		//$imageDir = realpath($publicDir."/images/");
		//Zend_Registry::set('img_dir', $imageDir);
		$imageDir = realpath($publicDir);
		Zend_Registry::set('img_dir', $imageDir);
	}

	protected function _initUplloadFile() {
		ini_set('upload_max_filesize', '20M');
		ini_set('memory_limit', '1024M');
	    ini_set('post_max_size', '20M');
	    ini_set('max_input_time', 300);
	    ini_set('max_execution_time', 300);
    }
}