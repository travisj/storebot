<?php                                                                                                                                                                                                    

class CartController extends BaseController
{
    function init()
    {   
        
    }   
        
    public function indexAction()
	{
		if ($this->_request->isPost()) {
			switch ($this->_getParam('submit_type')) {
				case 'Update':
					Cart::save($this->_getParam('cart'));	
					$this->view->cart_updated = true;
					break;
				case 'Pay Now With PayPal':
					// Create instance of the phpPayPal class
					$paypal = new phpPayPal();

					// Set the amount total for this order.
					$paypal->amount_total = '50.49';

					// You can manually set the return and cancel URLs, or keep the one's pre-set in the class definition
					$paypal->return_url = 'http://fun:1234/cart/thank-you';
					$paypal->cancel_url = 'http://fun:1234/cart?cancel';

					// Make the request
					$paypal->set_express_checkout();

					// If successful, we need to store the token, and then redirect the user to PayPal
					if(!$paypal->_error)
					{
						// Store your token
						$_SESSION['token'] = $paypal->token;

						// Now go to PayPal
						$paypal->set_express_checkout_successful_redirect();
						exit;
					}
					break;
			}
		}

		$products = array();
		$cart = Cart::get();
		foreach ($cart['cart'] as $product_id => $items) {
			$products[$product_id] = Product::getById($product_id);
		}

		$this->view->products = $products;
		$this->view->cart = $cart;
		$this->view->pageTitle = 'Your Cart';
	}

	public function thankYouAction()
	{
		$paypal = new phpPayPal();
		var_dump($paypal);exit;
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

	public function removeItemAction()
	{
		$product_id = $this->_getParam('product_id');
		$item_id = $this->_getParam('item_id');

		Cart::removeItem($product_id, $item_id);
		$this->_redirect('/cart');
	}
}
