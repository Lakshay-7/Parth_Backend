<?php
    header("Access-Control-Allow-Origin: *");
	$memcache = new Memcache;
	$data = $memcache->get('memcache');
	echo "Data is ".$data;
	if($data!==FALSE){
		echo "<br /> value from cache";
		echo $data;	
	}
	else{
		$memcache->set('memcache',"value",5);
		echo "set memcache<br />";
	}
	$data = $memcache->get('memcache');
	echo $data;

	echo "<br /> sleep for 10sec";
	sleep(10);
	echo "<br />checking value again";
	$data = $memcache->get('memcache');
	echo $data;
?>
