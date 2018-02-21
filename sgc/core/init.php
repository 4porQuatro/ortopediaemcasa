<?php
	error_reporting(E_ALL);
	ini_set('display_errors', '1');

	if(!isset($_SESSION)) session_start();

	define('DS', DIRECTORY_SEPARATOR);
	define('APP_ROOT', dirname(dirname(dirname(__FILE__))));
	define('SGC_ROOT', APP_ROOT . DS . 'sgc');

	// base URL
	$protocol = stripos($_SERVER['SERVER_PROTOCOL'], 'https') === true ? 'https://' : 'http://';
	$base_path = $protocol . $_SERVER['HTTP_HOST'];
	define('APP_BASE_PATH', $base_path);

	// sgc autoloader
	function sgc_autoload($class_name){
		$filename = str_replace('_', DS, $class_name) . '.php';

		$core_path = SGC_ROOT . DS . 'core' . DS;
		$lib_path = $core_path . 'lib' . DS;

		if(file_exists($core_path . $filename)){
			require_once($core_path . $filename);
		}else if(file_exists($lib_path . $filename)){
			require_once($lib_path . $filename);
		}
	}
	spl_autoload_register('sgc_autoload');

	// require vendor autoload
	require_once(dirname(dirname(dirname(__FILE__))) . DS . 'vendor' . DS . 'autoload.php');
	require_once(SGC_ROOT . DS . 'core' . DS . 'env.php');
	require_once(SGC_ROOT . DS . 'core' . DS . 'helpers.php');

	require_once(SGC_ROOT . DS . 'scripts' . DS . 'connection.php');

	$template = new SGCTemplate();
