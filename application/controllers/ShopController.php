<?php                                                                                                                                                                                                    

class ShopController extends BaseController
{
    function init()
    {   
       $this->view->categories = Category::getAll(); 
    }   
        
    public function indexAction()
    { 
		$select = $this->db->select()
					->from('product');
		$adapter = new Zend_Paginator_Adapter_DbSelect($select);
		$this->view->paginator = new Zend_Paginator($adapter);
		$this->view->pageTitle = 'Shop';
	}

	public function notFoundAction()
	{
		var_dump('aoeuaoeu');exit;
	}

	public function showAction()
	{
		$slug = $this->_getParam('slug');
		try {
			$this->view->pageTitle = $product['name'];
			$this->view->product = Product::getBySlug($slug);
		} catch (ProductNotFoundException $e) {
			$this->_redirect('/shop/not-found');
		} catch (Exception $e) {
			var_dump($e);
		}
	}

	public function categoryAction()
	{
		$slug = $this->_getParam('slug');
		$page = $this->_getParam('page');

		try {
			$this->view->category = Category::getBySlug($slug);
			$this->view->products = Product::getAllByCategory($this->view->category['id'], $page, 2);
		} catch (CategoryNotFoundException $e) {
			$this->_redirect('/shop/not-found');
		} catch (Exception $e) {
			var_dump($e);
		}
	}
}
