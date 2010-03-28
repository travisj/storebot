<?php

class Product
{
	static public function getBySlug($slug)
	{
		return self::getByHelper('slug', $slug);
	}

	static public function getById($id)
	{
		return self::getByHelper('id', $id);
	}

	static private function getByHelper($get_by, $value)
	{
		$db = Zend_Registry::get('db');

		$product = $db->fetchRow(
			'SELECT * FROM product WHERE ' . $get_by . ' = ?',
			$value
		);

		if (!$product) {
			throw new ProductNotFoundException($slug . ' not found');
		}

		$product['items'] = $db->fetchAssoc(
			'SELECT * FROM item WHERE product_id = ?',
			$product['id']
		);

		$product['sold_out'] = true;
		foreach ($product['items'] as $item) {
			if ($item['quantity'] > 0) {
				$product['sold_out'] = false;
			}
		}

		return $product;
	}

	static public function getAllByCategory($category_id, $page, $per_page=20)
	{
		$db = Zend_Registry::get('db');

		$select = $db->select()
						->from('product')
						->where('active = 1')
						->where('category_id = ?', $category_id);
		$adapter = new Zend_Paginator_Adapter_DbSelect($select);
		return new Zend_Paginator($adapter);
	}

	static public function checkStock($item_id, $quantity)
	{
		$db = Zend_Registry::get('db');
		$items_in_stock = $db->fetchOne('SELECT quantity FROM item WHERE id = ?', $item_id);

		return $items_in_stock >= $quantity;
	}
}


class ProductNotFoundException extends Exception {}
class OutOfStrockException extends Exception {}
