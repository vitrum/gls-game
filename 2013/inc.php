<?php
date_default_timezone_set('Asia/Shanghai');
define('SECURE_KEY', 'GLAMOUR');
$login = FALSE;
$customer_id = $customer_name = $customer_email = $securecode = NULL;

if(isset($_GET['customer_id']) && isset($_GET['name']) 
	&& isset($_GET['securecode']) && isset($_GET['email'])){
	$customer_id = $_GET['customer_id'];
	$customer_name = urldecode($_GET['name']);
	$securecode = $_GET['securecode'];
	if($securecode === md5(SECURE_KEY.$customer_id.$customer_name)){
		$login = TRUE;
	}
	$customer_email = urldecode($_GET['email']);
}