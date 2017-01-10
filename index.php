<?php

error_reporting(E_ALL);

require_once 'cms.php';
require_once 'model.php';
require_once 'functions.php';

foreach(scandir($models_dir='models') as $file){
	$node=explode('.php',$file)[0];
	$file=$models_dir . '/' . $file;
	
	if(!is_dir($file) && substr($file,-4)=='.php'){
		require_once $file;
		$node::install();
	}
}

require_once 'login.php';

if(empty(CMS::$path=explode('/',CMS::$uri=$_GET['page'])) || empty(CMS::$path[0])) CMS::$path=['home','index'];

foreach(CMS::$path as $node){
	if(!empty($found))
		CMS::$params[]=$node;
	else
		$found=file_exists($file=(CMS::$file.='/' . $node) . '.php');
}
if(empty(CMS::$params)) CMS::$params[]='index';

ob_start();
if($pass=$found){
	require_once $file;
	if($pass=is_callable($function='Controller::' . CMS::$params[0]))
		call_user_func_array($function,array_slice(CMS::$params,1));
}
if(!$pass)
	echo CMS::view('information',[
		'title'=>'404 Error',
		'message'=>'File Not Found'
	]);
CMS::$yield=ob_get_clean();

require_once 'template.php';

?>