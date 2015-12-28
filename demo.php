<?php
	
	require_once(dirname(__FILE__).DIRECTORY_SEPARATOR.'httpClient.class.php');
	define('KEY','XXXX');
	define('SECRET', 'XXX');
	define('HOST','XXXX');

	$file_name  = 'XXXX'; // 文件上传路径
	$apex = new apexApi(KEY, SECRET, HOST);
	$apex->uuid = microtime();
	$api = 'third.membercard.uploadAvatar';

	$data['post'] = 'post';
	$data['img'] = '@'.$file_name;
	$ret = $apex->api($api,$data,'POST',true);
