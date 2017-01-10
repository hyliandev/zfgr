<?php

class CMS {
	// == FUNCTIONS ==
	// ===============
	
	public static function debug($v){
		return '<pre>' . print_r($v,true) . '</pre>';
	}
	
	public static function view($view,$vars=[]){
		foreach($vars as $key=>$value){
			$$key=$value;
		}
		
		unset($vars);
		
		ob_start();
		require_once 'views/' . $view . '.php';
		return ob_get_clean();
	}
	
	public static function passwordHash($str){
		return password_hash($str,PASSWORD_BCRYPT);
	}
	
	
	
	
	
	// == VARIABLES ==
	// ===============
	
	public static $DB;
	public static $DB_prefix='zfgr_';
	
	public static $site_title='ZFGR';
	public static $site_title_fill='Zelda Fan Game Resource';
	
	public static $uri='';
	public static $path=[];
	public static $file='controllers';
	public static $params=[];
	
	public static $yield='';
}

try {
	CMS::$DB=new PDO('mysql:host=localhost;dbname=zfgr','root','');
}
catch(Exception $e){
	die('<h1>DATABASE ERROR:</h1><pre>' . $e . '</pre>');
}

?>