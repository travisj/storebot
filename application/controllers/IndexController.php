<?php                                                                                                                                                                                                    

class IndexController extends BaseController
{
    function init()
    {   
        
    }   
        
    public function indexAction()
    { 
		$this->view->pageTitle = 'Home';
	}
}
