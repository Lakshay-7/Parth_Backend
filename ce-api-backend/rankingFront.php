<?php
    header("Access-Control-Allow-Origin: *");
	$memcache = new Memcached;
	$data = $memcache->get('rankingsFront');
	if($data!==FALSE){
		echo $data;	
	}
	else{	
		$rankingFront = "";
		require "conn.php";
		$rankingFront.= '{';
		$arrMatchTypes = ["test","odi","t20"];
		$arrPlayTypes = ["batting","bowling","allrounder"];

		for($i=0;$i<3;$i++){
			$temp = $arrMatchTypes[$i];
			$sql = "SELECT `name`,`flag`,`".$temp."_rating` as `rating`,`".$temp."_points` as `points`,`".$temp."_matches` as `matches` FROM `international_teams` WHERE `".$temp."_rating`=(SELECT max(`".$temp."_rating`) FROM `international_teams`)";
			$res = $conn->query($sql);
			$data=$res->fetch_array();
		$rankingFront.= '"'.$temp.'_topper":'.json_encode($data).",";
		}

		$sql = "SELECT `name`,`flag`,`w_rating` as `rating`,`w_points` as `points`,`w_matches` as `matches` FROM `international_teams` WHERE `w_rating`=(SELECT max(`w_rating`) FROM `international_teams`)";
		$res = $conn->query($sql);
		$data=$res->fetch_array();
		$rankingFront.= '"w_topper":'.json_encode($data).",";




		for($k=0;$k<2;$k++){
			for($i=0;$i<3;$i++){
				for($j=0;$j<3;$j++){
					if(!($k==1&&$i==0)){

					$temp = $arrMatchTypes[$i]."_".$arrPlayTypes[$j];
					$tempX = ($k==0) ? $temp : "w_".$temp ;
					$rankingFront.= '"'.$tempX.'":';
					$sql = "SELECT `name`,`".$temp."` as `rating`,`team`,`img` FROM `players` WHERE `".$temp."`=(SELECT max(`".$temp."`) FROM `players` WHERE `gender`=".$k.")";
					$res = $conn->query($sql);
					$data = $res->fetch_array();

					$rankingFront.= json_encode($data).",";
					}
				}
			}
		}

		$rankingFront.= '"status:":"ok"}';
		$memcache->set('rankingsFront',$rankingFront,600);
		echo $rankingFront;
	}
?>
