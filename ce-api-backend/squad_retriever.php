<?php
    header("Access-Control-Allow-Origin: *");
	$memcache = new Memcached;
	$s_id=$_REQUEST['id'];
	$squadData = $memcache->get("squad_".$s_id);
	if($squadData!=FALSE){
		echo $squadData;
	}else{
		require "conn.php";
		$sql="select group_id,series_id,group_name,player_name from squad_players where series_id='$s_id'";
		$result=mysqli_query($conn,$sql);
		$obj = new stdClass();
		while($row = $result->fetch_assoc()){
			$g_name=$row['group_name'];
			if(!isset($obj->$g_name)){
				$obj->$g_name = array();
			}
			array_push($obj->$g_name , $row);
		}
		echo json_encode($obj);
		$memcache->set("squad_".$s_id,json_encode($obj),600);
	}
?>
