<?php
class Core_Db_Adapter {
	const ADAPTER_REGISTRY = 'adapter';

	/**
	 * regis db adapter
	 * @param unknown $bootstrap
	 */
	static public function init($bootstrap) {
		$resource = $bootstrap->getPluginResource('db');
		$adapter = $resource->getDbAdapter();
		Zend_Registry::set(self::ADAPTER_REGISTRY, $adapter);
	}

	/**
	 * get db dapater
	 * @return mixed
	 */
	static public function getAdapter() {
		return Zend_Registry::get(self::ADAPTER_REGISTRY);
	}
}
