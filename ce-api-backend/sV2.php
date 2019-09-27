<?php
    header("Access-Control-Allow-Origin: *");
	$memcache = new Memcached;
	$key = $_REQUEST['key'];
	
	$data = $memcache->get('sV2_'.$key);
	if($data!==FALSE){
		echo $data;	
	}
	else{
		$data = file_get_contents("https://cricket-exchange-5.firebaseio.com/sV2/".$key.".json"); 
		$memcache->set('sV2_'.$data,5);
		echo $data;
	}
?>
