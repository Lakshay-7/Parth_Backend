<?php
    header("Access-Control-Allow-Origin: *");
	$conn = mysqli_connect("localhost","root","","CricketExchange");
	if ($conn->connect_error) {
    	die("Connection failed: " . $conn->connect_error);
	} 

	$m_id=$_REQUEST['id'];
	$sql= "select * from um_matches where id= $m_id" ;

		$table = $conn->query($sql);
		$arr= array();
		while($value = $table->fetch_assoc()){
			array_push($arr, $value);
		}
		$json_value=json_encode($arr);
		echo $json_value;
?>
