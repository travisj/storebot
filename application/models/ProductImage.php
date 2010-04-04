<?php

class ProductImage
{
	static public function upload($file_type, $file_contents, $addl_file_name='')
	{
		$image_id = md5($file_contents);
		$file_name = $image_id . $addl_file_name . '.' . $file_type;

		$sb = Zend_Registry::get('sb');
		$s3 = new Zend_Service_Amazon_S3($sb->aws_key, $sb->aws_secret_key);
		$resp = $s3->putObject(
				$sb->aws_bucket . '/' . $sb->aws_path . '/' . $file_name, 
				$file_contents,
				array(Zend_Service_Amazon_S3::S3_ACL_HEADER =>
					  Zend_Service_Amazon_S3::S3_ACL_PUBLIC_READ)
				);

		return $image_id;
	}
}
