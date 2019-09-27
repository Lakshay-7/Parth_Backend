<?php
    header("Access-Control-Allow-Origin: *");
	$rankingKey = "";
    $arr = [];
    $category = $_REQUEST['category'];
	$rankingKey.=$category;
    $gender = $_REQUEST['gender'];
	$rankingKey.=$gender;
    if(isset($_REQUEST['type'])){
		$type = $_REQUEST['type'];
		$rankingKey.=$type;
	}
        
    if(isset($_REQUEST['play'])){
		$play = $_REQUEST['play'];
		$rankingKey.=$play;
	}
        
    if(isset($_REQUEST['page'])){
        $page = $_REQUEST ['page'];
        $limit1 = ($page-1)*20;
        $rankingKey.=$limit1;
    }
	$memcache = new Memcached;
	$data = $memcache->get("WEB".$rankingKey);
	if($data!==FALSE){
		
		echo $data;	
	}
	else{
		require "conn.php";
		$rankingValue = "";
		if($category=="team"&&$gender=="men"){
		   $temp = $type;
			$sql = "SELECT `name`,`rflag`,`".$temp."_rating` as `rating`,`".$temp."_points` as `points`,`".$temp."_matches` as `matches` FROM `international_teams` WHERE `".$temp."_rating` !=0 ORDER BY `".$temp."_rating` DESC";
			$res = $conn->query($sql);
			while($data=$res->fetch_array()){
				array_push($arr,$data);
			}
		}

		if($category=="team"&&$gender=="women"){

		   $temp = "w";
			$sql = "SELECT `name`,`rflag`,`".$temp."_rating` as `rating`,`".$temp."_points` as `points`,`".$temp."_matches` as `matches` FROM `international_teams` WHERE `".$temp."_rating` !=0 ORDER BY `".$temp."_rating` DESC";
			$res = $conn->query($sql);
			while($data=$res->fetch_array()){
				array_push($arr,$data);
			}
		}
		if($category=="player"){
			$k=0;
			if($gender=="women")$k=1;
				$temp = $type."_".$play;
			$sql = "SELECT `players`.`name`,`players`.`".$temp."` as `rating`,`international_teams`.`short_name` as `team`, `international_teams`.`rflag` as `flag` FROM `players` INNER JOIN `international_teams` ON `players`.`team`=`international_teams`.`short_name`  WHERE `players`.`".$temp."`!=0 AND `players`.`gender`=".$k." ORDER BY `".$temp."` DESC LIMIT ".$limit1.",20";

			$res = $conn->query($sql);
			while($data=$res->fetch_array()){
				$rankingValue.= json_encode($data);
				array_push($arr,$data);
			}
		}
		$memcache->set("WEB".$rankingKey,json_encode($arr),600);
		echo json_encode($arr);
	}

?>
