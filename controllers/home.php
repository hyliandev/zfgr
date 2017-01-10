<?php

class Controller {
	public static function index(){
		echo CMS::view('home');
	}
	
	public static function test(){
		echo 'haha';
	}
}

?>