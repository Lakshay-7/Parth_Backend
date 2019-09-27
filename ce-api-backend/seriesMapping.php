<?php
    header("Access-Control-Allow-Origin: *");
	$memcache = new Memcached;
	$data = $memcache->get('seriesMapping');
	if($data!==FALSE){
		echo $data;	
	}
	else{
		$data = file_get_contents("https://cricket-exchange-5.firebaseio.com/sEr135IDMapping.json"); 
		$memcache->set('seriesMapping',$data,600);
		echo $data;
	}
?>
