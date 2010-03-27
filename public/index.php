<?php                                                                                                                                                                                                    
ob_start("ob_gzhandler", 4096);

set_include_path(
    '../library' . 
    PATH_SEPARATOR . '../library/Zend/library' . 
    PATH_SEPARATOR . '../' .
    PATH_SEPARATOR . '../application/' .
    PATH_SEPARATOR . '../application/models/' .
    PATH_SEPARATOR . '../application/plugins/' .
    PATH_SEPARATOR . get_include_path()); 

require_once 'config.php';

require_once 'Zend/Loader/Autoloader.php';
Zend_Loader_Autoloader::getInstance()->setFallbackAutoloader(true);

$db = new Zend_Db_Adapter_Pdo_Mysql(array(
	'host'		=> $sb->mysql_host,
	'username'	=> $sb->mysql_user,
	'password'	=> $sb->mysql_pass,
	'dbname'	=> $sb->mysql_name
));

Zend_Registry::set('db', $db);
Zend_Registry::set('sb', $sb);
                
require_once "config.php";
session_start(); 

Zend_Layout::startMvc(array('layoutPath' => '../application/views/layouts'));

require_once("../application/controllers/BaseController.php");
$front = Zend_Controller_Front::getInstance();
$front->setControllerDirectory('../application/controllers');
$front->dispatch();
