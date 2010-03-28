<?php

class Cart
{
	static public function add($product_id, $item_id, $quantity)
	{
		$db = Zend_Registry::get('db');

		$product = Product::getById($product_id);
		$desc = $product['name'] . ' - ' . $product['items'][$item_id]['name'];

		// make sure we have enough items in stock
		if (!Product::checkStock($item_id, $quantity)) {
			throw new OutOfStrockException('You can not add more than ' . $product['items'][$item_id]['quantity'] . ' items to your cart at this time.');
		}

		$cart = self::get();
		if (isset($cart[$product_id])) {
			if (isset($cart[$product_id][$item_id])) {
				$cart[$product_id][$item_id] += $quantity;
			} else {
				$cart[$product_id][$item_id] = $quantity;
			}
		} else {
			$cart[$product_id] = array();
			$cart[$product_id][$item_id] = $quantity;
		}
		return self::save($cart);
	}

	static public function getUuid()
	{
		if (!$_SESSION['cart_uuid']) {
			$_SESSION['cart_uuid'] = trim(`/usr/bin/uuidgen`);
		}
		return $_SESSION['cart_uuid'];
	}

	static public function get()
	{
		$db = Zend_Registry::get('db');

		$cart = $db->fetchOne('SELECT items FROM cart WHERE uuid = ?', self::getUuid());

		if (!$cart) {
			$cart_array = array();
		} else {
			$cart_array = json_decode($cart, true);
		}

		return $cart_array;
	}

	static public function save($cart)
	{
		$db = Zend_Registry::get('db');

		if (is_array($cart)) {
			$cart = json_encode($cart);
		}

		$uuid = self::getUuid();
		if (!$db->update('cart', array('items'=>$cart), array('uuid = ?'=>$uuid))) {
			return $db->insert('cart', array('uuid'=>$uuid, 'items'=>$cart));
		}
		return true;
	}
}
