<?php                                                                                                                                                                                                    

class ProductController extends BaseController
{
    function init()
    {   
        
    }   
        
    public function indexAction()
    { 
		$this->view->pageTitle = 'Home';
	}

	public function notFoundAction()
	{
		var_dump('aoeuaoeu');exit;
	}

	public function showAction()
	{
		$slug = $this->_getParam('slug');

		$product = $this->db->fetchRow(
			'SELECT * FROM product WHERE slug = ?',
			$slug
		);

		if (!$product) {
			$this->_redirect('/product/not-found');
		}

		$product['items'] = $this->db->fetchAll(
			'SELECT * FROM item WHERE product_id = ?',
			$product['id']
		);

		$sold_out = true;
		foreach ($product['items'] as $item) {
			if ($item['quantity'] > 0) {
				$sold_out = false;
			}
		}
		$this->view->sold_out = $sold_out;

		$this->view->product = $product;
		$this->view->pageTitle = $product['name'];
	}
}

