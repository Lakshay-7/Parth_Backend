<?php
    header("Access-Control-Allow-Origin: *");
	$memcache = new Memcached;
	$t_id=$_GET['id'];
	$page=$_GET['page'];
	$key = "team_matches_".$t_id."p".$page;
	$matchesData = $memcache->get($key);
	if($matchesData!=FALSE){
		echo "key is ".$key;
		echo "<br />";
		echo $matchesData;
	}else{
		require "conn.php";
		$db = $conn;
		$sql1="select team_name from um_teams where id='$t_id'";
		$answer=mysqli_query($db,$sql1);
		$answer_row=$answer->fetch_assoc();
		$team_name=$answer_row['team_name'];
		$sql="select * from um_matches where team1='$team_name' OR team2='$team_name' ";
			if($page<0){
				$page*=-1;
				$offset = $page*40;
				$sql.= "AND `date_time` < now() ORDER BY `date_time` DESC,`series_name`limit 40 OFFSET $offset ";			
			}else if($page==0){
				$sql1 = $sql;
				$sql2 = $sql;
				$sql1.= "AND `date_time` < now() ORDER BY `date_time` DESC,`series_name`limit 40 OFFSET 0 ";			
				$sql2.= "AND `date_time` >= now() ORDER BY `date_time`,`series_name`limit 40 OFFSET 0";			
				$sql = "(".$sql1.") UNION (".$sql2.")";
			}else if($page>0){
				$offset = $page*40;
				$sql.= "AND `date_time` >= now() ORDER BY `date_time`,`series_name`limit 40 OFFSET $offset ";			
			}
			$arr = array();
			$res = $db->query($sql);
			$obj = new stdClass();
			while($data = $res->fetch_assoc()){
				$date = $data['date_time'];
				$date =  date('Y/m/d',strtotime($date));
				$series_name = $data['series_name'];
				if(!isset($obj->$series_name->$date)){
					if(!isset($obj->$series_name))
						$obj->$series_name = new stdClass();
					$obj->$series_name->$date = array();
				}
				array_push($obj->$series_name->$date,$data);
			}
			echo json_encode($obj);		
			$memcache->set($key,json_encode($obj),600);
	}


?>
