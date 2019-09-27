<?php
	//$conn = new mysqli("localhost","root","","CricketExchange");
	$conn = new mysqli(null, "root", "Jidenna@1985", "CricketExchange", null, "/cloudsql/cricket-exchange:us-central1:usinstance");
	if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

//echo "Connected successfully";
?>