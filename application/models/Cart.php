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

		$cart = self::get(false);
		if (isset($cart[$product_id])) {
			if (isset($cart[$product_id][$item_id])) {
				$cart[$product_id][$item_id]['quantity'] += $quantity;
			} else {
				$cart[$product_id][$item_id]['quantity'] = $quantity;
			}
		} else {
			$cart[$product_id] = array();
			$cart[$product_id][$item_id]['quantity'] = $quantity;
		}
		return self::save($cart);
	}

	static public function removeItem($product_id, $item_id)
	{
		$cart = self::get(false);
		unset($cart[$product_id][$item_id]);
		return self::save($cart);
	}

	static public function getUuid()
	{
		if (!$_SESSION['cart_uuid']) {
			$_SESSION['cart_uuid'] = trim(`/usr/bin/uuidgen`);
		}
		return $_SESSION['cart_uuid'];
	}

	static public function get($with_meta=true)
	{
		$db = Zend_Registry::get('db');

		$cart = $db->fetchOne('SELECT items FROM cart WHERE uuid = ?', self::getUuid());

		$cart_array = array();
		if (!$cart) {
			$cart_array['cart'] = array();
		} else {
			$cart_array['cart'] = json_decode($cart, true);
		}

		$cart_array['meta']['max_item_warning'] = false;
		$cart_array['meta']['total']  = 0;
		foreach ($cart_array['cart'] as $product_id => $items) {
			$product = Product::getById($product_id);
			foreach ($items as $item_id => $detail) {
				$cart_array['cart'][$product_id][$item_id]['subtotal'] = $detail['quantity'] * $product['items'][$item_id]['price'];
				$cart_array['cart'][$product_id][$item_id]['max_items'] = false;
				if ($detail['quantity'] > $product['items'][$item_id]['quantity']) {
					$cart_array['cart'][$product_id][$item_id]['max_items'] = $product['items'][$item_id]['quantity'];
					$cart_array['meta']['max_item_warning'] = true;
				} else {
					$cart_array['meta']['total'] += $detail['quantity'] * $product['items'][$item_id]['price'];
				}
			}
		}

		return $with_meta ? $cart_array : $cart_array['cart'];
	}

	static public function save($cart)
	{
		$db = Zend_Registry::get('db');

		if (is_array($cart)) {
			foreach ($cart as $product_id => $items) {
				foreach ($items as $item_id => $detail) {
					unset($cart[$product_id][$item_id]['max_items']);
					unset($cart[$product_id][$item_id]['subtotal']);
				}
			}
			$cart = json_encode($cart);
		}

		$uuid = self::getUuid();
		if (!$db->update('cart', array('items'=>$cart), array('uuid = ?'=>$uuid))) {
			return $db->insert('cart', array('uuid'=>$uuid, 'items'=>$cart));
		}
		return true;
	}
}
