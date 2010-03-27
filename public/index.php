<?php                                                                                                                                                                                                    
ob_start("ob_gzhandler", 4096);

set_include_path(
    '../library' . 
    PATH_SEPARATOR . '../' .
    PATH_SEPARATOR . '../application/' .
    PATH_SEPARATOR . '../application/models/' .
    PATH_SEPARATOR . '../application/plugins/' .
    PATH_SEPARATOR . get_include_path()); 

require_once 'Zend/Loader/Autoloader.php';
Zend_Loader_Autoloader::getInstance()->setFallbackAutoloader(true);
                
require_once "config.php";
session_start(); 

Zend_Layout::startMvc(array('layoutPath' => '../application/views/layouts'));

$front = Zend_Controller_Front::getInstance();
$front->registerPlugin(new AuthenticationPlugin());
$front->setControllerDirectory('../application/controllers');
$front->dispatch();
