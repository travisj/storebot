<?php                                                                                                                                                                                                    

class CartController extends BaseController
{
    function init()
    {   
        
    }   
        
    public function indexAction()
	{
		$products = array();
		$cart = Cart::get();
		foreach ($cart as $product_id => $items) {
			$products[$product_id] = Product::getById($product_id);
		}

		$this->view->products = $products;
		$this->viwe->cart = $cart;
		$this->view->pageTitle = 'Your Cart';
	}

	public function addToCartAction()
	{
		$status = $msg = '';

		$product_id = $this->_getParam('product_id');
		$item_id = $this->_getParam('item_id');
		$quantity = $this->_getParam('quantity');

		try {
			Cart::add($product_id, $item_id, $quantity);
			$status = 'OK';
		} catch (Exception $e) {
			$status = 'Error';
			$msg = $e->getMessage();
		}
		$this->_helper->json(array('status'=>$status, 'msg'=>$msg));
	}
}
