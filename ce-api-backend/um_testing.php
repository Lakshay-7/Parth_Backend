<?php
	header("Access-Control-Allow-Origin: *");

	$memcached = new Memcached;
	$wise = $_REQUEST['wise'];
	$type = $_REQUEST['type'];
	$page = $_REQUEST['page'];
	$key = $wise.$type.$page;
	$umData = $memcached->get($key);
	if($umData!=FALSE){
		echo $umData;
	}else{
			require "conn.php";
			if($wise=="date"){
				$sql = "SELECT * FROM `um_matches`";

				if($type=="all"){
					$sql.=" WHERE 1 ";
				}else if($type=="international"){
					$sql.=" WHERE `international`=1 ";
				}else if($type=="t20"){
					$sql.=" WHERE `t20`=1 ";
				}else if($type=="odi"){
					$sql.=" WHERE `odi`=1 ";
				}else if($type=="test"){
					$sql.=" WHERE `test`=1 ";
				}else if($type=="men"){
					$sql.=" WHERE `men`=1 ";
				}else if($type=="women"){
					$sql.=" WHERE `women`=1 ";
				}else if($type=="league"){
					$sql.=" WHERE `league`=1 ";
				}else if($type=="domestic"){
					$sql.=" WHERE `domestic`=1 ";
				}


				if($page<0){
					$page*=-1;
					$offset = $page*50;
					$sql.= "AND `date_time` < now() ORDER BY `date_time` DESC,`series_name`limit 50 OFFSET $offset ";	
					
				}else if($page==0){
					$sql1 = $sql;
					$sql2 = $sql;


					$sql1.= "AND `date_time` < now() ORDER BY `date_time` DESC,`series_name`limit 20 OFFSET 0 ";			
					$sql2.= "AND `date_time` >= now() ORDER BY `date_time`,`series_name`limit 20 OFFSET 0";		


					$sql = "(".$sql1.") UNION (".$sql2.")";

				}else if($page>0){
					$offset = $page*50;
					$sql.= "AND `date_time` >= now() ORDER BY `date_time`,`series_name`limit 50 OFFSET $offset ";			

				}$sql = "SELECT * FROM (".$sql.") T ORDER BY T.`date_time` ASC";

				$arr = array();
				//echo $sql."<br />";
				$res = $conn->query($sql);
				$obj = new stdClass();
				while($data = $res->fetch_assoc()){
					$date = $data['date_time'];

					$date =  date('Y/m/d',strtotime($date));

					$series_name = $data['series_name'];
					if(!isset($obj->$date->$series_name)){
						if(!isset($obj->$date))
							$obj->$date = new stdClass();
						$obj->$date->$series_name = array();
					}
					array_push($obj->$date->$series_name,$data);
				}
				$memcached->set($key,json_encode($obj),600);
				echo json_encode($obj);
					
			}

			if($wise=="series"){

				$sql = "SELECT * FROM `um_series`";

				if($type=="all"){
					$sql.=" WHERE 1 ";
				}else if($type=="international"){
					$sql.=" WHERE `international`=1 ";
				}else if($type=="t20"){
					$sql.=" WHERE `t20`=1 ";
				}else if($type=="odi"){
					$sql.=" WHERE `odi`=1 ";
				}else if($type=="test"){
					$sql.=" WHERE `test`=1 ";
				}else if($type=="men"){
					$sql.=" WHERE `men`=1 ";
				}else if($type=="women"){
					$sql.=" WHERE `women`=1 ";
				}else if($type=="league"){
					$sql.=" WHERE `league`=1 ";
				}else if($type=="domestic"){
					$sql.=" WHERE `domestic`=1 ";
				}

				$month = date('m',time());
				$year = date('Y',time());

				if($page>=0){
					$sm=($page)*6+$month;
					$fm=$sm+5;
					$sy=$year;
					$fy=$year;
					if($sm>12){
						$sy=$year+(int)($sm/12);
						if($sm%12==0)
							$sm=12;
						else
							$sm=$sm%12;
					}
					if($fm>12){
						$fy=$year+(int)($fm/12);
						if($fm%12==0)
							$fm=12;
						else
							$fm=$fm%12;
					}
				}
				else{
					$page*=-1;
					$fm=$month-($page*6);
					$sm=$fm+5;
					$sy=$year;
					$fy=$year;
					if($fm<=0){
						$p=-1*$fm;
						$fy=$year-((int)($p/12)+1);
						if($p%12==0)
							$fm=12;
						else
							$fm=12-($p%12);
					}
					if($sm<=0){
						$p=-1*$sm;
						$sy=$year-((int)($p/12)+1);
						if($p%12==0)
							$sm=12;
						else
							$sm=12-($p%12);
					}
					$a=$sm;
					$sm=$fm;
					$fm=$a;
					$a=$sy;
					$sy=$fy;
					$fy=$a;
				}

				$start_limit = $sy."-".$sm."-"."01";
				$finish_limit = $fy."-".$fm."-"."31";
				$sql.= "and `start_date`>= '$start_limit' && `start_date` <= '$finish_limit' ORDER BY `start_date`";
				//echo $sql."</br>";
				//echo "<hr />";

				$arr = array();
				$res = $conn->query($sql);
				$obj = new stdClass();
				while($data = $res->fetch_assoc()){
					$month = $data['start_date'];
					$month = date('F',strtotime($month));
					$year = $data['start_date'];
					$year = date('Y',strtotime($year));
					$combined=$month." ".$year;
					//echo $combined;

					if(!(isset($obj->$combined))){
						$obj->{$combined} = array();
					}

					$temp = new stdClass();
					$temp->{'id'}=$data['id'];
					$temp->{'series_name'}=$data['series_name'];
					$temp->{'time_period'}=date('jS F',strtotime($data['start_date']))." to ".date('jS F',strtotime($data['end_date']));

					array_push($obj->{$combined},$temp);					
				}
					$memcached->set($key,json_encode($obj),600);
					echo json_encode($obj);
			}

			if($wise=="teams"||$wise=="team"){

				$sql = "SELECT * FROM `um_teams`";

				if($type=="all"){
					$sql.=" WHERE 1 ";
				}else if($type=="international"){
					$sql.=" WHERE `international`=1 ";
				}else if($type=="t20"){
					$sql.=" WHERE `t20`=1 ";
				}else if($type=="odi"){
					$sql.=" WHERE `odi`=1 ";
				}else if($type=="test"){
					$sql.=" WHERE `test`=1 ";
				}else if($type=="men"){
					$sql.=" WHERE `men`=1 ";
				}else if($type=="women"){
					$sql.=" WHERE `women`=1 ";
				}else if($type=="league"){
					$sql.=" WHERE `league`=1 ";
				}else if($type=="domestic"){
					$sql.=" WHERE `domestic`=1 ";
				}
				$sql.=" AND NOT `team_name`='TBC' ORDER BY international DESC, id asc";
//				echo $sql."<hr />";
				$arr = array();
				$res = $conn->query($sql);
				while($data = $res->fetch_assoc()){
					$obj = new stdClass();
					$obj->{'team_name'}=$data['team_name'];
					$obj->{'flag'}=$data['flag'];
					$obj->{'id'}=$data['id'];
					array_push($arr,$obj);			
				}
				echo json_encode($arr);
				$memcached->set($key,json_encode($arr),3600);
			}


	}
	
?>
