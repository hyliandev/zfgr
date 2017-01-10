<?php

session_start();

if(empty(CMS::$session=Session::get(
	'*',
	'session_id=' . CMS::$DB->quote(session_id()),
	1
))){
	CMS::$session=new Session();
	CMS::$session->session_id=session_id();
	CMS::$session->uid=0;
	CMS::$session->user_agent=$_SERVER['HTTP_USER_AGENT'];
	CMS::$session->save();
}

?>