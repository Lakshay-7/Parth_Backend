<?php
    header("Access-Control-Allow-Origin: *");
	$memcache = new Memcache;
	$key = $_REQUEST['key'];
	$data = $memcache->get('info2_'.$key);
	if($data!==FALSE){
		echo $data;	
	}
	else{
		$data = file_get_contents("https://cricket-exchange.firebaseio.com/info/".$key.".json"); 
		$memcache->set('info2_'.$key,$data,60);
		echo $data;
	}
?>
