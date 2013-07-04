<?php
if(!ONLINE_MODE){
	return array(
			'host'=>'localhost',
			'user'=>'root',
			'password'=>'root',
			'database'=>'hengdeli'
	);
}else{
	return array(
        'host'=>'localhost',
        'user'=>'ziliang',
        'password'=>'mysqlzl',
        'database'=>''
	);
}