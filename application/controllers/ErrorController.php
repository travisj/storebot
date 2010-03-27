<?php                                                                                                                                                                                                    

class ErrorController extends BaseController
{
    function init() 
    {           
            
    }   
    
    public function errorAction()
    {   
        $this->view->errors = $this->_getParam('error_handler');
    }               
}
