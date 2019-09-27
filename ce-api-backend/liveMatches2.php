<?php
    header("Access-Control-Allow-Origin: *");
	$memcache = new Memcached;
//	$key = $_REQUEST['key'];
	
	$data = $memcache->get('liveMatches2');
	if($data!==FALSE){
		echo $data;	
	}
	else{
		$data = file_get_contents("https://cricket-exchange-5.firebaseio.com/liveMatches2.json"); 
		$memcache->set('liveMatches2',$data,5);
		echo $data;
	}
?>
