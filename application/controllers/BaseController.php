<?php                                                                                                                                                                                                    

abstract class BaseController extends Zend_Controller_Action
{
	public function preDispatch()
	{
		$this->db = Zend_Registry::get('db');
		$this->sb = Zend_Registry::get('sb');

		$this->view->application_name = $this->sb->application_name;
	}
}
