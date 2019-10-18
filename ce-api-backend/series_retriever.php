<?php
    header("Access-Control-Allow-Origin: *");
	$memcache = new Memcached;
	$s_id=$_REQUEST['id'];
  $s_type=$_REQUEST['type'];

	$seriesData = $memcache->get("series_".$s_id);
	if($seriesData!=FALSE){
		echo $seriesData;
	}else{
		require "conn.php";
		//$sql= "select * FROM um_matches WHERE series_name IN (SELECT series_name FROM um_series WHERE id = $s_id)";
    if($s_type!NULL){
      $sql= "select * FROM um_matches WHERE series_name IN (SELECT series_name FROM um_series WHERE id = $s_id) and $s_type = 1";
    }else{
      $sql= "select * FROM um_matches WHERE series_name IN (SELECT series_name FROM um_series WHERE id = $s_id)";
    }
		$table = $conn->query($sql);
		$arr= array();
		while($value = $table->fetch_assoc()){
			array_push($arr, $value);
		}
		$json_value=json_encode($arr);
		echo $json_value;
		$memcache->set("series_".$s_id,$json_value,600);
	}
?>
