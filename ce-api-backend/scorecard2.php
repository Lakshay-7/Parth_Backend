<?php
    header("Access-Control-Allow-Origin: *");
	$memcache = new Memcached;
	$key = $_REQUEST['key'];
	
	$data = $memcache->get('scorecard2_'.$key);
	if($data!==FALSE){
		echo $data;	
	}
	else{
		$data = file_get_contents("https://cricket-exchange.firebaseio.com/scorecards/".$key.".json" ); // false for synchronous request
		$memcache->set('scorecard2_'.$key,$data,60);
		echo $data;
	}
?>
