<?php
	
	include './ErrorHandle.class.php';

	class demo extends ErrorHandle {

		public function __construct () {
			parent::__construct();
		}

		public function test () {
			throw new Exception("Error Processing Request", 1);  //抛出异常
			// echo $a;  //notice 级别错误
		}
	}


	$one = new demo();
	$one->test();

	$one->say();  
	//调用egister_shutdown_function　 注意PHP需要把调用的函数存入内存中，当所有的函数执行完毕之后执行该函数，注意，在这个时候从内存中调用，不是从ＰＨＰ页面中调用，所以如果有路径信息，应使用绝对路径，因为ＰＨＰ已经当原来的页面不存在了。就没有什么相对路径可言。注意，在这个时候从内存中调用，不是从ＰＨＰ页面中调用，所以如果有路径信息，应使用绝对路径，因为ＰＨＰ已经当原来的页面不存在了。就没有什么相对路径可言。
	