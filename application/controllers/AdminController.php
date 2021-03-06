<?php                                                                                                                                                                                                    

class AdminController extends BaseController
{
    function init()
    {   
        
    }   
        
    public function indexAction()
	{
		$this->view->products = $this->db->fetchAll('SELECT * FROM product');
	}

	public function categoryAction()
	{
		if ($this->_request->isPost()) {
			$category = $this->_getParam('category');
			$this->db->insert('category', $category);
		}

		$this->view->categories = $this->db->select()->from('category')->order('name ASC')->query()->fetchAll();
	}

	public function productAction()
	{
		$id = $this->_getParam('id');
		if ($id) {
			$this->view->type = 'update';

			$product = $this->db->fetchRow('SELECT * FROM product WHERE id = ?', $id);
			$product['items'] = $this->db->fetchAll('SELECT * FROM item WHERE product_id = ?', $product['id']);

			$this->view->product = $product;
		} else {
			$this->view->type = 'add';
		}

		$this->view->categories = $this->db->select()->from('category')->order('name ASC')->query()->fetchAll();
	}

	public function addProductAction()
	{
		if ($this->_request->isPost()) {
			$product = $this->_getParam('product');
			try {
				$this->db->beginTransaction();
				$this->db->insert('product', $product);

				$product_id = $this->db->lastInsertId();
				$newitems = $this->_getParam('newitem');

				foreach ($newitems as $item) {
					$item['product_id'] = $product_id;
					$this->db->insert('item', $item);
				}
				$this->db->commit();
				$this->_redirect('/admin/product?id=' . $product_id);
			} catch (Exception $e) {
				$this->db->rollBack();
				var_dump($e);exit;
			}
		} else {
			$this->_redirect('/admin');
		}
	}

	public function updateProductAction()
	{
		if ($this->_request->isPost()) {
			$product = $this->_getParam('product');
			try {
				$this->db->beginTransaction();
				$this->db->update('product', $product, array('id = ?' => $product['id']));

				$items = $this->_getParam('item');
				foreach ($items as $key => $item) {
					if (isset($item['delete'])) {
						$this->db->delete('item', array('id = ?'=>$key));
					} else {
						$this->db->update('item', $item, array('id = ?'=>$key));
					}
				}
				
				$newitems = $this->_getParam('newitem');
				foreach ($newitems as $item) {
					if ($item['name'] != '') {
						$item['product_id'] = $product['id'];
						$this->db->insert('item', $item);
					}
				}
				$this->db->commit();
				$this->_redirect('/admin/product?id=' . $product['id']);
			} catch (Exception $e) {
				$this->db->rollBack();
				var_dump($e);exit;
			}
		} else {
			$this->_redirect('/admin');
		}
	}

	public function uploadProductImageAction()
	{
		$status = $msg = $url = '';

		$image = $_FILES['image'];
		$file_type = substr($image['type'], strpos($image['type'], '/') + 1);
		try {
			$url = ProductImage::upload($file_type, file_get_contents($image['tmp_name']));
			$status = 'OK';
		} catch (Exception $e) {
			$status = 'Error';
			$msg = $e->getMessage();
		}
		$this->_helper->json(array('status'=>$status, 'msg'=>$msg, 'url'=>$url));
	}

	public function productImagesAction()
	{
		$id = $this->_getParam('id');

		if ($this->_request->isPost()) {
			$desc = $this->_getParam('description');

			if ($_FILES['small']['size'] && $_FILES['medium']['size'] && $_FILES['large']['size']) {
				$file_type = array_pop(explode('.', $_FILES['large']['name']));
				$image_id = ProductImage::upload($file_type, file_get_contents($_FILES['large']['tmp_name']), '_l');
				ProductImage::upload($file_type, file_get_contents($_FILES['medium']['tmp_name']), '_m');
				ProductImage::upload($file_type, file_get_contents($_FILES['small']['tmp_name']), '_s');

				$this->db->insert('images', array('id'=>$image_id, 'product_id'=>$id, 'description'=>$desc, 'type'=>$file_type));
			}
		}
		$this->view->product = Product::getById($id);

		$this->view->current_images = $this->db->fetchAll(
			'SELECT * FROM images WHERE product_id = ?',
			$id
		);
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
