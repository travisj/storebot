<?php                                                                                                                                                                                                    

class AdminController extends BaseController
{
    function init()
    {   
        
    }   
        
    public function indexAction()
	{
	}

	public function productAction()
	{
		if ($this->_request->isPost()) {
			$product = $this->_getParam('product');
			try {
				$this->db->beginTransaction();
				$this->db->insert('product', $product);

				$product_id = $this->db->lastInsertId();
				$items = $this->_getParam('item');

				foreach ($items as $item) {
					$item['product_id'] = $product_id;
					$this->db->insert('item', $item);
				}
				$this->db->commit();
			} catch (Exception $e) {
				$this->db->rollBack();
				var_dump($e);exit;
			}


			$this->_redirect('/product/' . $product['slug']);
		}
	}

	public function loginAction()
	{
		if ($this->_request->isPost()) {
			$login = $this->_getParam('login');

			$user = $this->db->fetchRow(
				'SELECT * FROM user WHERE username = ? and password = ?',
				array(
					$login['username'],
					md5($login['password'])
				)
			);
			$_SESSION['user'] = $user;

			$this->_redirect('/admin/');
		}
	}

	public function userAction()
	{
		if ($this->_request->isGet()) {
			// nothing to see here
		} else if ($this->_request->isPost()) {
			$user = $this->_getParam('user');
		
			$this->db->insert('user', array(
				'username' => $user['username'],
				'password' => md5($user['password']),
				'email' => $user['email']
			));

			$this->_redirect('/admin/');
		}
	}
}
