<?php                                                                                                                                                                                                    

abstract class BaseController extends Zend_Controller_Action
{
	public function preDispatch()
	{
		$this->view->addHelperPath('application/views/helpers', 'StoreBot_View_Helper_');
		$this->db = Zend_Registry::get('db');
		$this->sb = $this->view->sb = Zend_Registry::get('sb');

		$this->view->application_name = $this->sb->application_name;

		$this->view->controller = $this->_getParam('controller');                                                                                                                                        
		$this->view->action = $this->_getParam('action');
	}
}
