<?php
    header("Access-Control-Allow-Origin: *");
	$memcache = new Memcached;
	$key = $_REQUEST['key'];
	$data = $memcache->get('info_'.$key);
	if($data!==FALSE){
		echo $data;	
	}
	else{
		
		$data = file_get_contents("https://cricket-exchange.firebaseio.com/info/".$key.".json"); 
		$memcache->set('info_'.$key,$data,60);
		echo $data;
	}
?>
