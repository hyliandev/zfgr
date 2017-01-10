<?php

class Sessions extends Model {
	public static $data=[
		'uid'=>[
			'datatype'=>'int unsigned'
		],
		
		'user_agent'=>[
			'datatype'=>'varchar(255)'
		],
		
		'session_id'=>[
			'datatype'=>'varchar(255)'
		]
	];
	
	public static $table='sessions';
}

?>