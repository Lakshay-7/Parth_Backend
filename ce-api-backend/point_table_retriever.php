<?php
    header("Access-Control-Allow-Origin: *");
	$memcache = new Memcached;
	$s_id=$_REQUEST['s_id'];
	$pointTableData = $memcache->get("pointstable_".$s_id);
	if($pointTableData!=FALSE){
		echo $pointTableData;
	}else{
		require "conn.php";
		$sql="select point_table_teams.id,point_table_teams.series_id,point_table_teams.group_id,point_table_teams.team_name,point_table_teams.betfair_name,point_table_teams.P,point_table_teams.W,point_table_teams.L,point_table_teams.NR,point_table_teams.Pts,point_table_teams.NRR,point_table_teams.cuprate,point_table_groups.group_name from point_table_teams INNER JOIN point_table_groups ON point_table_teams.group_id=point_table_groups.group_id where point_table_teams.series_id='$s_id'  ORDER BY CAST(point_table_teams.pts as decimal(11,3)) DESC, CAST(point_table_teams.NRR as decimal(11,3)) DESC"; 

			$arr = array();
			$res = $conn->query($sql);
			$obj = new stdClass();
			while($data = $res->fetch_assoc()){

				$series_id = $data['series_id'];

				$group_name = $data['group_name'];

				if(!isset($obj->$group_name)){
					$obj->$group_name = array();
				}
				array_push($obj->$group_name,$data);
			}
			echo json_encode($obj);
		$memcache->set("pointstable_".$s_id,json_encode($obj),600);
	}
?>
