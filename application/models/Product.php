<?php

class Product
{
	static public function getBySlug($slug)
	{
		$db = Zend_Registry::get('db');

		$product = $db->fetchRow(
			'SELECT * FROM product WHERE slug = ?',
			$slug
		);

		if (!$product) {
			throw new ProductNotFoundException($slug . ' not found');
		}

		$product['items'] = $db->fetchAll(
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
}


class ProductNotFoundException extends Exception {}
