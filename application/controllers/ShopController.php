<?php                                                                                                                                                                                                    

class ShopController extends BaseController
{
    function init()
    {   
        
    }   
        
    public function indexAction()
    { 
		$select = $this->db->select()
					->from('product');
		$adapter = new Zend_Paginator_Adapter_DbSelect($select);
		$this->view->paginator = new Zend_Paginator($adapter);
		$this->view->pageTitle = 'Shop';
	}
}
