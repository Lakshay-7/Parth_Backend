<?php
    header("Access-Control-Allow-Origin: *");
	$memcache = new Memcached;
	$data = $memcache->get('teamMapping');
	if($data!==FALSE){
		echo $data;	
	}
	else{
		$data = file_get_contents("https://cricket-exchange-5.firebaseio.com/t34mMapping.json"); 
		$memcache->set('teamMapping',$data,600);
		echo $data;
	}
?>
