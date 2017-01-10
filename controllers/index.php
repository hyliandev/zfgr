<?php

class Controller {
	public static function index(){
		$user=User::get();
		$user->username='lololol';
		$user->password='dsjfasasdfsadfdf';
		die(CMS::debug($user->save()));
		die(CMS::debug($user));
		
		echo CMS::view('home');
	}
}

?>