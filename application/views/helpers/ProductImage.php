<?php

class StoreBot_View_Helper_ProductImage extends Zend_View_Helper_Abstract
{
	public function productImage($image_id, $size)
	{
		$sb = Zend_Registry::get('sb');
		$format = Zend_Registry::get('db')->fetchOne('SELECT type FROM images WHERE id = ?', $image_id);
		return '<img src="http://' . $sb->aws_bucket . '/' . $sb->aws_path . '/' . $image_id . '_' . $size . '.' . $format . '" />';
	}
}
