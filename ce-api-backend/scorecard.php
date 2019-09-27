<?php
    header("Access-Control-Allow-Origin: *");
	$memcache = new Memcached;
	$key = $_REQUEST['key'];
	
	$data = $memcache->get('scorecard_'.$key);
	if($data!==FALSE){
		echo $data;	
	}
	else{
		error_log("requesting scorecard".$key." from ce-5 ".time());
		$data = file_get_contents("https://cricket-exchange.firebaseio.com/scorecards/".$key.".json" ); // false for synchronous request
		$memcache->set('scorecard_'.$key,$data,20);
		echo $data;
	}
?>
