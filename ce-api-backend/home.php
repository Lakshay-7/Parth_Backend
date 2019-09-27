<?php
    header("Access-Control-Allow-Origin: *");
	$memcache = new Memcached;
	$data = $memcache->get('live_series');
	if($data!==FALSE){
		echo $data;	
	}
	else{
		require "conn.php";
		$sql="select id,series_name from um_series where keep_on_home='1'";
		$answer=$conn->query($sql);
		$arr_live=array();
		while($row=$answer->fetch_assoc()){
			$id=$row['id'];
			$series_name=$row['series_name'];
			$obj = new stdClass();
			$obj->{'id'}=$id;
			$obj->{'series_name'}=$series_name;
			array_push($arr_live, $obj);
		}
		
		$live_series = json_encode($arr_live);
		$memcache->set('live_series',$live_series,600);
		echo $live_series;
	}
?>
