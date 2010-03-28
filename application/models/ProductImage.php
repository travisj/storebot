<?php

class ProductImage
{
	static public function upload($file_name, $file_contents)
	{
		$sb = Zend_Registry::get('sb');
		$s3 = new Zend_Service_Amazon_S3($sb->aws_key, $sb->aws_secret_key);

		$s3->putObject($sb->aws_bucket . '/' . $sb->aws_path . '/' . $file_name, $file_contents);
	}
}
