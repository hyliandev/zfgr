<?php

class Session extends Model {
	public static $data=[
		'uid'=>[
			'datatype'=>'int unsigned'
		],
		
		'user_agent'=>[
			'datatype'=>'varchar(255)'
		],
		
		'session_id'=>[
			'datatype'=>'varchar(255)',
			'unique'=>true
		]
	];
	
	public static $table='sessions';
}

?>