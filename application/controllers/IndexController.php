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

	public function aboutAction()
	{
		$this->view->pageTitle = 'About';
	}

	public function policiesAction()
	{
		$this->view->pageTitle = 'Policies';
	}
}
