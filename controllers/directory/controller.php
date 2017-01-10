<?php

class Controller {
	public static function index(){
		echo 'hi';
	}
	
	public static function f($v1=null,$v2=null,$v3=null,$v4=null){
		echo $v1 . ' ' . $v2 . ' ' . $v3 . ' ' . $v4;
	}
}

?>