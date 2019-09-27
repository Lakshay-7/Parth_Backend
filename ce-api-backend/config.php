<?php
    header("Access-Control-Allow-Origin: *");
	$memcache = new Memcached;
	$data = $memcache->get('config');
	if($data!==FALSE){
		echo $data;	
	}
	else{
		$data = file_get_contents("https://cricket-exchange.firebaseio.com/config.json"); 
		$memcache->set('config',$data,600);
		echo $data;
	}
?>
