<?php

class Category 
{
	static public function getAll()
	{
		$db = Zend_Registry::get('db');

		return $db->fetchAll('SELECT * FROM category ORDER BY name');
	}
	static public function getBySlug($slug)
	{
		$db = Zend_Registry::get('db');

		$category = $db->fetchRow('SELECT * FROM category WHERE slug = ?', $slug);

		if (!$category) {
			throw new CategoryNotFoundException();
		}

		return $category;
	}
}
class CategoryNotFoundException extends Exception {}
